<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestOrderProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'request_order_products';
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

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function requestOrder(){
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function getRemainingQtyAttribute()
    {
        // Total qty yang diminta pada RequestOrderProduct ini
        $totalRequested = $this->qty;

        // Hitung total qty yang sudah diproses pada semua RequestProcessProduct dengan product_id & request_order_id yang sama
        $processedQty = \App\Models\RequestProcessProduct::where('product_id', $this->product_id)
            ->whereHas('requestProcess', function ($q) {
                $q->where('request_order_id', $this->request_order_id);
            })
            ->sum('qty');

        return max(0, $totalRequested - $processedQty);
    }

    // Relasi ke RequestProcessProduct
    public function requestProcessProducts()
    {
        return $this->hasMany(\App\Models\RequestProcessProduct::class, 'product_id', 'product_id')
            ->whereHas('requestProcess', function ($q) {
                $q->where('request_order_id', $this->request_order_id);
            });
    }
}
