<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockMeter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'stock_meters';
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
    
    public static function analyticProductsInLocation($loc_type, $loc_id)
    {
        // Ambil semua stock meter sesuai lokasi dan tipe
        $results = Self::selectRaw('
                nowin_id,
                nowin_type,
                product_id,
                SUM(length) as length_total,
                SUM(sold_length) as length_sold,
                SUM(length) - SUM(sold_length) as length_remaining,
                COUNT(*) as qty,
                SUM(CASE WHEN is_onway = 1 THEN 1 ELSE 0 END) as qty_onway,
                SUM(CASE WHEN is_onway = 0 THEN 1 ELSE 0 END) as qty_remaining
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
                'qty' => (int) $item->qty,
                'qty_onway' => (int) $item->qty_onway,
                'qty_remaining' => (int) $item->qty_remaining,
                'length_total' => (int) $item->length_total,
                'length_remaining' => (int) $item->length_remaining,
                'length_sold' => (int) $item->length_sold,
                'location' => $item->getLocation(),
            ];
        })->values();
    }

    public static function analyticProductInLocation($loc_type, $loc_id, $productId)
    {
        $results = Self::selectRaw('
                nowin_id,
                nowin_type,
                product_id,
                SUM(length) as length_total,
                SUM(sold_length) as length_sold,
                SUM(length) - SUM(sold_length) as length_remaining,
                COUNT(*) as qty,
                SUM(CASE WHEN is_onway = 1 THEN 1 ELSE 0 END) as qty_onway,
                SUM(CASE WHEN is_onway = 0 THEN 1 ELSE 0 END) as qty_remaining
            ')
            ->where('product_id', $productId)
            ->where('nowin_type', $loc_type)
            ->where('nowin_id', $loc_id)
            ->groupBy('nowin_id', 'nowin_type', 'product_id')
            ->get();

         $stockDatas = Self::where('product_id', $productId)
            ->where('nowin_type', $loc_type)
            ->where('nowin_id', $loc_id)
            ->get();


        $response = $results->map(function($item) use ($stockDatas) {
            return (object) [
                'product_id' => $item->product_id,
                'qty' => (int) $item->qty,
                'qty_onway' => (int) $item->qty_onway,
                'qty_remaining' => (int) $item->qty_remaining,
                'length_total' => (int) $item->length_total,
                'length_remaining' => (int) $item->length_remaining,
                'length_sold' => (int) $item->length_sold,
                'details' =>  $stockDatas,
                'updated_at' => $item->updated_at,
            ];
        })->values();

        return $response;
    }

    public static function analyticLocationsFromProduct($productId)
    {
        // Ambil semua lokasi (nowin_type, nowin_id) beserta qty_remaining untuk produk tertentu
        $results = Self::selectRaw('
                nowin_type,
                nowin_id,
                SUM(CASE WHEN is_onway = 0 THEN 1 ELSE 0 END) as qty_remaining
            ')
            ->where('product_id', $productId)
            ->groupBy('nowin_type', 'nowin_id')
            ->get();

        return $results->map(function($item) {
            return [
                'location_type' => $item->nowin_type,
                'location_id' => $item->nowin_id,
                'qty_remaining' => (int) $item->qty_remaining,
            ];
        })->values();
    }
}
