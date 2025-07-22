<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'stocks';
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
            
        });
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getLocation(){
        return $this->nowin_type == 'warehouse' ? Warehouse::find($this->nowin_id) : Store::find($this->nowin_id);
    }

    public function analytics(){
        return Self::selectRaw('
            nowin_id,
            nowin_type,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END) as stock_in,
            SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) as stock_out,
            SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END) as stock_onway,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->where('product_id', $this->product_id)
            ->groupBy('nowin_id', 'nowin_type')
            ->get();
    }

    public static function totalRemainingByProduct($productId)
    {
        return (int) Self::where('product_id', $productId)
            ->selectRaw('
                SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->value('stock_remaining');
    }

    public static function totalRemainingByProductIn($productId, $type = 'store')
    {
        return (int) Self::where('product_id', $productId)->where('nowin_type', $type)
            ->selectRaw('
                SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->value('stock_remaining');
    }

    public static function analyticProducts(){
        return Self::selectRaw('
            nowin_id,
            nowin_type,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END) as stock_in,
            SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) as stock_out,
            SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END) as stock_onway,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->groupBy('nowin_id', 'nowin_type')
            ->get()
            ->keyBy('nowin_id');
    }

    public static function analyticLocationsFromProduct($productId){
        return Self::selectRaw('
            nowin_id,
            nowin_type,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END) as stock_in,
            SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) as stock_out,
            SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END) as stock_onway,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->where('product_id', $productId)
            ->groupBy('nowin_id', 'nowin_type')
            ->get()
            ->keyBy('nowin_id');
    }

    public static function analyticProductsInLocation($loc_type, $loc_id){
        $results = Self::selectRaw('
            nowin_id,
            nowin_type,
            product_id,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END) as stock_in,
            SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) as stock_out,
            SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END) as stock_onway,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->where('nowin_type', $loc_type)
            ->where('nowin_id', $loc_id)
            ->groupBy('nowin_id', 'nowin_type', 'product_id')
            ->get();

        return $results->map(function($item) {
            return (object) [
                'id' => $item->nowin_id,
                'product_id' => $item->product_id,
                'product' => optional($item->product),
                'stock_in' => (int) $item->stock_in,
                'stock_out' => (int) $item->stock_out,
                'stock_onway' => (int) $item->stock_onway,
                'stock_remaining' => (int) $item->stock_remaining,
                'location' => $item->getLocation(),
            ];
        })->values();
    }

    public static function analyticProductInLocation($loc_type, $loc_id, $productId){
        $results = Self::selectRaw('
            nowin_id,
            nowin_type,
            product_id,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END) as stock_in,
            SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) as stock_out,
            SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END) as stock_onway,
            SUM(CASE WHEN trx_type = "in" THEN qty ELSE 0 END)
                - (SUM(CASE WHEN trx_type = "out" THEN qty ELSE 0 END) + SUM(CASE WHEN trx_type = "onway" THEN qty ELSE 0 END))
                as stock_remaining
            ')
            ->where('product_id', $productId)
            ->where('nowin_type', $loc_type)
            ->where('nowin_id', $loc_id)
            ->groupBy('nowin_id', 'nowin_type', 'product_id')
            ->get();

        return $results->map(function($item) {
            return (object) [
                'stock_in' => (int) $item->stock_in,
                'stock_out' => (int) $item->stock_out,
                'stock_onway' => (int) $item->stock_onway,
                'stock_remaining' => (int) $item->stock_remaining,
            ];
        })->values();
    }
}
