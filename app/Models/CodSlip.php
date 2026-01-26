<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodSlip extends Model
{
    protected $fillable = [
    'order_id',
    'customer_name',
    'mobile',
    'alt_mobile',
    'address',
    'cod_amount',
    'scheme',
    'post_type',
    'customer_id',
];

public function order()
{
    return $this->belongsTo(Order::class);
}

}
