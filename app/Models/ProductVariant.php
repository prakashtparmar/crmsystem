<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','sku','weight','price','sale_price','is_default','is_active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batchLots()
    {
        return $this->hasMany(BatchLot::class);
    }
}
