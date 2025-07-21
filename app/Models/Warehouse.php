<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Warehouse extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'warehouses';
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
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

    public function getTotalBalAttribute()
    {
        return Bal::where('nowin_type', 'warehouse')->where('is_unpack', 0)->where('nowin_id', $this->id)->count();
    }

    public function bals()
    {
        return Bal::whereHas('receive.purchase', function ($query) {
                $query->where('warehouse_id', $this->id);
            })->with('products')->get();
    }

   public function products()
   {
      $stockProductIds = $this->wstocks()->pluck('product_id')->all();
      $stockMeterProductIds = $this->wstockmeters()->pluck('product_id')->all();

      $productIds = collect($stockProductIds)
         ->merge($stockMeterProductIds)
         ->unique()
         ->values();

      return Product::whereIn('id', $productIds)->get();
   }

    public function analytics(){
        $total_remaining = 0;
        $total_in = 0;
        $total_out = 0;
        $total_onway = 0;
        $total_remaining = 0;
        $stocks = $this->wstocks();

        foreach ($stocks as $stock) {
            $total_remaining += $stock->stock_remaining;
            $total_in += $stock->stock_in;
            $total_out += $stock->stock_out;
            $total_onway += $stock->stock_onway;
        }

        $wstocks = $this->wstockmeters();
        $ws_total = 0;
        $ws_onway = 0;
        $ws_remaining = 0;
        foreach ($wstocks as $wstock) {
            $ws_total += $wstock->qty;
            $ws_onway += $wstock->qty_onway;
            $ws_remaining += $wstock->qty_remaining;
        }

        return (object) [
            'stock' => (object) [
                'total_remaining' => $total_remaining,
                'total_in' => $total_in,
                'total_out' => $total_out,
                'total_onway' => $total_onway,
                'total_product' => $stocks->count(),
            ],
            'meteran' => (object) [
                'total_product' => $wstocks->count(),
                'total_in' => $ws_total,
                'total_onway' => $ws_onway,
                'total_remaining' => $ws_remaining,
            ],
            'total_bal' => $this->total_bal,
        ];
    }

    public function getProductRemaining($product_id)
    {
        $product = Product::find($product_id);
        $remainingQty = 0;
        if($product?->type->type == 'satuan'){
            $stocks = $this->wstocks()->where('product_id', $product_id);
            foreach ($stocks as $stock) {
                $remainingQty += $stock->stock_remaining;
            }
        }else{
            $stocks = $this->wstockMeters()->where('product_id', $product_id);
            foreach ($stocks as $stock) {
                $remainingQty += $stock->qty_remaining;
            }
        }

        return max(0, $remainingQty);
    }

    public function wstocks(){
        return Stock::analyticProductsInLocation('warehouse', $this->id);
    }

    public function wstockmeters(){
        return StockMeter::analyticProductsInLocation('warehouse', $this->id);
    }
}
