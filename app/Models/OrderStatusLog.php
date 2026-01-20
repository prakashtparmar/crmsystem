<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id','from_status','to_status',
        'remarks','changed_by','changed_at'
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
