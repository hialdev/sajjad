<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'customer_orders';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
            $model->code = static::getCode();
        });
        static::deleting(function ($model) {
            $model->products()->delete();
        });
    }

    protected static function getCode()
    {
        $type = 'ORDER'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

   public function customer(){
      return $this->belongsTo(Customer::class, 'customer_id');
   }

   public function payment(){
      return $this->belongsTo(Payment::class, 'payment_id');
   }

   public function products(){
      return $this->hasMany(CustomerOrderProduct::class, 'customer_order_id');
   }

   public function partnerDebts(){
      return $this->hasMany(PartnerDebt::class, 'customer_order_id');
   }
}
