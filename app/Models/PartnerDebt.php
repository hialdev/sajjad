<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PartnerDebt extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'partner_debts';
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
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }
    public function customerOrderProduct(){
      return $this->belongsTo(CustomerOrderProduct::class, 'customer_order_product_id');
    }
    public function getPartnerAttribute(){
      return $this->customerOrderProduct->partner;
    }
}
