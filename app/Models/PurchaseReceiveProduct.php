<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseReceiveProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_receive_products';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
        static::deleting(function ($model) {
            //
        });
    }

    public function receive(){
        return $this->belongsTo(PurchaseReceive::class, 'purchase_receive_id');
    }

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_product_id');
    } 
}
