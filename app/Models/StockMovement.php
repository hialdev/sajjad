<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockMovement extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'stock_movements';
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
            if ($model->products->count() > 0){
                $model->products()->delete();
            }
        });
    }

    protected static function getCode()
    {
        $type = 'MOVE'; // Purchase Order Principal
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transport(){
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function files(){
        return $this->hasMany(StockMovementFile::class, 'movement_id');
    }

    public function products(){
        return $this->hasMany(StockMovementProduct::class, 'movement_id');
    }

    public function getFromAttribute(){
        return $this->from_type == 'warehouse' ? Warehouse::find($this->from_id) : Store::find($this->from_id);
    }

    public function getToAttribute(){
        return $this->to_type == 'store' ? Store::find($this->to_id) : Warehouse::find($this->to_id);
    }

}
