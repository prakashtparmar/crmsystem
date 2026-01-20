<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchLot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id','batch_number','manufactured_at','quantity'
    ];

    protected $dates = ['manufactured_at'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function expiry()
    {
        return $this->hasOne(Expiry::class);
    }
}
