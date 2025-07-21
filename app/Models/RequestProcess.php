<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestProcess extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'request_process';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = self::getCode();
        });
        static::deleting(function ($model) {
            $model?->transport?->delete();
            $model?->products?->each->delete();
        });
    }

    protected static function getCode()
    {
        $type = 'PROCESS'; // Process Request Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function products(){
        return $this->hasMany(RequestProcessProduct::class, 'request_process_id');
    }

    public function requestOrder(){
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function clientInvoice(){
        return $this->hasOne(RequestOrderInvoice::class, 'purchase_order_id');
    }
}
