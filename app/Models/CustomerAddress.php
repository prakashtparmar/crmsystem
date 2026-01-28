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
        $this->label ? 'Label: ' . $this->label : null,
        $this->contact_name ? 'Name: ' . $this->contact_name : null,
        $this->contact_phone ? 'Phone: ' . $this->contact_phone : null,

        $this->address_line1 ? 'Address Line 1: ' . $this->address_line1 : null,
        $this->address_line2 ? 'Landmark: ' . $this->address_line2 : null,

        $this->post_office ? 'Post Office: ' . $this->post_office : null,
        $this->village ? 'Village: ' . $this->village : null,
        $this->taluka ? 'Taluka: ' . $this->taluka : null,
        $this->district ? 'District: ' . $this->district : null,

        ($this->state || $this->pincode)
            ? 'State & Pincode: ' . trim(($this->state ?? '') . ' ' . ($this->pincode ?? ''))
            : null,

        $this->country ? 'Country: ' . $this->country : null,

        $this->region ? 'Region: ' . $this->region : null,
        $this->area ? 'Area: ' . $this->area : null,
        $this->route ? 'Route: ' . $this->route : null,
        $this->beat ? 'Beat: ' . $this->beat : null,
        $this->territory ? 'Territory: ' . $this->territory : null,
        $this->zone ? 'Zone: ' . $this->zone : null,
        $this->warehouse ? 'Warehouse: ' . $this->warehouse : null,
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
