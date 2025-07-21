<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseReceive extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'purchase_receives';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Set UUID otomatis sebelum menyimpan data
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->code = static::getCode();
        });
        static::deleting(function ($model) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
        });
    }

    protected static function getCode()
    {
        $type = 'PO-RCV'; // Purchase Order Client
        $lastRecord = self::whereYear('date', '=', date('Y'))
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastRecord ? intval(explode('/', $lastRecord->code)[1]) : 0;

        $newNumber = $lastNumber + 1;

        return generateCode($type, $newNumber); // Fungsi generateCode dengan nilai default
    }

    public function purchase(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(){
        return $this->hasMany(PurchaseReceiveProduct::class, 'purchase_receive_id');
    }

    public function bals(){
        return $this->hasMany(Bal::class, 'purchase_receive_id');
    }

    public function availableQtyBale()
    {
        $balIds = Bal::where('purchase_receive_id', $this->purchase_receive_id)
            ->pluck('id');

        $usedQty = BalProduct::whereIn('bal_id', $balIds)
            ->where('product_id', $this->product_id)
            ->sum('qty');

        return max(0, $this->qty - $usedQty);
    }
}
