<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BalProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'bal_products';
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

    public function bal(){
        return $this->belongsTo(Bal::class, 'bal_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
