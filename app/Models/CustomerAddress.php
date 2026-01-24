<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'type',          // billing | shipping | both
        'is_default',
        'label',         // Home, Office, Farm, Warehouse, etc.

        // Contact
        'contact_name',
        'contact_phone',

        // Address
        'address_line1',
        'address_line2',
        'village',
        'taluka',
        'district',
        'state',
        'country',
        'pincode',
        'post_office',

        // Location / Routing
        'region',
        'area',
        'route',
        'beat',
        'territory',
        'zone',
        'warehouse',

        // Geo
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
    ];

    /**
     * Parent Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Enterprise-grade formatted address block
     * Used for Order snapshots, invoices, shipments, PDFs, etc.
     */
    public function formatted(): ?string
    {
        return implode("\n", array_filter([
            $this->contact_name,
            $this->address_line1,
            $this->address_line2,
            $this->village,
            $this->taluka,
            $this->district,
            trim(($this->state ?? '') . ($this->pincode ? ' - ' . $this->pincode : '')),
            $this->country,
            $this->contact_phone ? 'Phone: ' . $this->contact_phone : null,
        ]));
    }

    /**
     * Scope helpers
     */
    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
