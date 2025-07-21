<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerOrderProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'customer_order_products';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
        static::deleting(function ($model) {
            
        });
    }

    public function customerOrder(){
      return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

   public function partner(){
      return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function product(){
      return $this->belongsTo(Product::class, 'product_id');
    }

   public function payment(){
      return $this->hasOne(Payment::class, 'customer_order_product_id', 'id');
   }
}
