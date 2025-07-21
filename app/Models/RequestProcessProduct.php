<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestProcessProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'request_process_products';
    public $incrementing = false;
    protected $appends = ['remaining_qty'];
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

    public function purchase(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function product(){
      return $this->belongsTo(Product::class, 'product_id');
    }

    public function getRemainingQtyAttribute()
    {
        // Ambil total qty dari parent RequestOrderProduct
        $requestOrderProduct = $this->requestOrderProduct;
        if (!$requestOrderProduct) {
            return 0;
        }

        $totalRequested = $requestOrderProduct->qty;

        // Hitung total qty yang sudah diproses pada semua RequestProcessProduct dengan product_id & request_order_id yang sama
        $processedQty = self::where('product_id', $this->product_id)
            ->whereHas('requestProcess', function ($q) {
                $q->where('request_order_id', $this->requestProcess->request_order_id);
            })
            ->sum('qty');

        return max(0, $totalRequested - $processedQty);
    }

    // Relasi ke RequestOrderProduct (asumsi foreign key: product_id & request_order_id)
    public function requestOrderProduct()
    {
        return $this->hasOne(RequestOrderProduct::class, 'product_id', 'product_id')
            ->where('request_order_id', $this->requestProcess->request_order_id);
    }

    // Relasi ke RequestProcess
    public function requestProcess()
    {
        return $this->belongsTo(RequestProcess::class, 'request_process_id');
    }
}
