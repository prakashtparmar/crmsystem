<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id','carrier','tracking_number',
        'shipped_at','delivered_at','address_snapshot','status'
    ];

    protected $casts = [
        'shipped_at' => 'date',
        'delivered_at' => 'date',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
