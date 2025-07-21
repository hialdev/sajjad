<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Bal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'bals';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = static::getCode();
        });
        static::deleting(function ($model) {
            if ($model->unpack) $model->unpack->delete();
            if ($model->products->count() > 0) $model->products()->delete();

            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

    protected static function getCode()
    {
        $type = 'BAL'; // Purchase Order Client
        $lastRecord = self::orderBy('created_at', 'desc')->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function receive(){
        return $this->belongsTo(PurchaseReceive::class, 'purchase_receive_id');
    }

    public function unpack(){
        return $this->hasOne(BalUnpack::class, 'bal_id');
    }

    public function products(){
        return $this->hasMany(BalProduct::class, 'bal_id');
    }

    public function nowin(){
        $type = $this->nowin_type;
        switch ($type) {
            case 'warehouse':
                return $this->belongsTo(Warehouse::class,'nowin_id','id');
                break;
            case 'store':
                return $this->belongsTo(Store::class,'nowin_id','id');
                break;
            default:
                return null;
                break;
        }
    }
}
