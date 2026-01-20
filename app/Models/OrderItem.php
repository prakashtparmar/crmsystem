<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','product_name',
        'price','quantity','tax_rate','tax_amount',
        'discount_amount','total'
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
