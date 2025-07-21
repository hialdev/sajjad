<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseOrderProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_order_products';
    public $incrementing = false;
    protected $appends = ['remaining_receive'];
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

    public function pack(){
        return $this->belongsTo(Pack::class, 'pack_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function receiveProducts(){
        return PurchaseReceiveProduct::where('purchase_product_id', $this->id)->get();
    }

    public function getRemainingReceiveAttribute(){
        $left = $this->qty - $this->receiveProducts()->sum('receive_qty');
        if($left < 0) return 0;
        return $left;
    }

    public function availableQtyBale($receive_id = null)
    {
        $balIds = Bal::whereHas('receive.purchase', fn ($q) => $q->where('purchase_order_id', $this->purchase_order_id))
            ->pluck('id');

        $usedQty = BalProduct::whereIn('bal_id', $balIds)
            ->where('product_id', $this->product_id)
            ->sum('qty');

        return max(0, $this->qty - $usedQty);
    }
}
