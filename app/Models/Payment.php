<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory;
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection = 'osano';
    protected $table = 'payments';
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
    
}
