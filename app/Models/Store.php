<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'stores';
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
        return Bal::where('nowin_type', 'store')->where('is_unpack', 0)->where('nowin_id', $this->id)->count();
    }

    public function analytics(){
        $total_remaining = 0;
        $total_in = 0;
        $total_out = 0;
        $total_onway = 0;
        $total_remaining = 0;
        $stocks = $this->sstocks();

        foreach ($stocks as $stock) {
            $total_remaining += $stock->stock_remaining;
            $total_in += $stock->stock_in;
            $total_out += $stock->stock_out;
            $total_onway += $stock->stock_onway;
        }

        $sstocks = $this->sstockMeters();
        $ss_total = 0;
        $ss_onway = 0;
        $ss_sold = 0;
        $ss_remaining = 0;
        $length_remaining = 0;
        $length_sold = 0;
        $checksold = false;
        foreach ($sstocks as $sstock) {
            $ss_total += $sstock->qty;
            $ss_onway += $sstock->qty_onway;
            $checksold = $sstock->length_sold == ((int)$sstock->product->width * (int)$sstock->qty_remaining);
            if ($checksold) {
               $ss_sold += $sstock->qty_remaining;
            }
            $ss_remaining += $sstock->qty_remaining;
            $length_remaining += $sstock->length_remaining;
            $length_sold += $sstock->length_sold;
        }
        
        $response = (object) [
            'condition' => $checksold,
            'stock' => (object) [
                'total_remaining' => $total_remaining,
                'total_in' => $total_in,
                'total_out' => $total_out,
                'total_onway' => $total_onway,
                'total_product' => $stocks->count(),
            ],
            'meteran' => (object) [
                'total_product' => $sstocks->count(),
                'total_in' => $ss_total,
                'total_out' => $ss_sold,
                'total_onway' => $ss_onway,
                'total_remaining' => $ss_remaining,
                'length_sold' => $length_sold,
                'length_remaining' => $length_remaining,
            ],
            'total_bal' => $this->total_bal,
        ];
        //dd($response);
        return $response;
    }

    public function products()
   {
      $stockProductIds = $this->sstocks()->pluck('product_id')->all();
      $stockMeterProductIds = $this->sstockmeters()->pluck('product_id')->all();

      $productIds = collect($stockProductIds)
         ->merge($stockMeterProductIds)
         ->unique()
         ->values();

      return Product::whereIn('id', $productIds)->get();
   }

    public function getProductRemaining($product_id)
    {
        $product = Product::find($product_id);
        $remainingQty = 0;

        if($product?->type->type == 'satuan'){
            $stocks = $this->sstocks()->where('product_id', $product_id);
            foreach ($stocks as $stock) {
                $remainingQty += $stock->stock_remaining;
            }
        }else{

            $stocks = $this->sstockMeters()->where('product_id', $product_id);
            foreach ($stocks as $stock) {
                $remainingQty += $stock->qty_remaining;
            }
        }

        return $remainingQty;
    }

    public function sstocks(){
        return Stock::analyticProductsInLocation('store', $this->id);
    }

    public function sstockMeters(){
        return StockMeter::analyticProductsInLocation('store', $this->id);
    }
}
