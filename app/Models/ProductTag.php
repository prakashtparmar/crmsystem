<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id','tag'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
