<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid','order_code','customer_id','user_id',
        'order_date','expected_delivery_at',
        'sub_total','tax_amount','shipping_amount',
        'discount_amount','grand_total',
        'status','payment_status','payment_method','transaction_id',
        'customer_name','customer_email','customer_phone',
        'billing_address','shipping_address',
        'paid_at','completed_at','meta',
        'confirmed_at','cancelled_at',
        'created_by','updated_by',
    ];

    protected $casts = [
        'meta' => 'array',
        'order_date' => 'date',
        'expected_delivery_at' => 'date',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /* ---------------- Relations ---------------- */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Single Invoice per Order
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* ---------------- Helpers ---------------- */

    public function getTotalPaidAttribute()
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return max(0, $this->grand_total - $this->total_paid);
    }

    public function codSlip()
{
    return $this->hasOne(CodSlip::class);
}

}
