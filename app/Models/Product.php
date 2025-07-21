<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'products';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->slug = (string) Str::slug($model->name);
            $model->code = self::getCode($model->product_type_id);
        });
        static::updating(function ($model) {
            $model->slug = (string) Str::slug($model->name);
        });
        static::deleting(function ($model) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }
    
    protected static function getCode($type)
    {
        $type = ProductType::find($type)->code; // Purchase Order Principal
        $lastRecord = self::orderBy('created_at', 'desc')
                            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function type(){
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function getAnalyticLocation($locType, $locId){
        if($this->type->type == 'meteran'){
            return StockMeter::analyticProductInLocation($locType, $locId, $this->id);
        }

        return Stock::analyticProductInLocation($locType, $locId, $this->id);
    }

    public function getStockCountAttribute(){
        if ($this->type->type == 'meteran'){
            return StockMeter::where('product_id', $this->id)->where('is_onway', 0)->where('sold_length', 0)->count();
        }else{
            return Stock::totalRemainingByProduct($this->id);
        }
    }

    public function stockCountIn($type = 'store'){
        if ($this->type->type == 'meteran'){
            return StockMeter::where('product_id', $this->id)->where('is_onway', 0)->where('nowin_type', $type)->where('sold_length', 0)->count();
        }else{
            return Stock::totalRemainingByProductIn($this->id, $type);
        }
    }

   public function findMeterStockByLength($length, $locationType = null, $locationId = null)
   {
      if ($this->type->type !== 'meteran') {
         return collect(); // hanya berlaku untuk produk meteran
      }

      $query = \App\Models\StockMeter::where('product_id', $this->id)
         ->where('is_onway', 0)
         ->whereRaw('length - sold_length >= ?', [$length]);

      if ($locationType) {
         $query->where('nowin_type', $locationType);
      }

      if ($locationId) {
         $query->where('nowin_id', $locationId);
      }

      return $query->get();
   }

    // public function getRemainingAndLocations() {
    //     if ($this->type->type == 'meteran'){
    //         return StockMeter::;
    //     }else{
    //         return Stock::totalRemainingByProduct($this->id);
    //     }
    // }
    
}
