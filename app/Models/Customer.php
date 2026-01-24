<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'customer_code',

        // Identity
        'first_name',
        'middle_name',
        'last_name',
        'display_name',
        'mobile',
        'email',

        // Extra Contacts
        'phone_number_2',
        'relative_phone',
        'secondary_contact_name',
        'secondary_contact_phone',

        // Classification
        'type',
        'category',

        // Business
        'company_name',
        'gst_number',
        'pan_number',
        'business_type',
        'registration_number',
        'website',
        'established_year',
        'annual_turnover',

        // Address (Legacy / Primary)
        'address_line1',
        'address_line2',
        'village',
        'taluka',
        'district',
        'state',
        'country',
        'pincode',
        'post_office',
        'latitude',
        'longitude',

        // Agriculture
        'land_area',
        'land_unit',
        'primary_crops',
        'secondary_crops',
        'irrigation_type',
        'farming_method',
        'soil_type',

        // Finance
        'credit_limit',
        'outstanding_balance',
        'credit_valid_till',
        'payment_terms',
        'preferred_payment_mode',
        'bank_name',
        'account_number',
        'ifsc_code',
        'billing_cycle',

        // References
        'referred_by',
        'reference_type',
        'reference_name',
        'reference_phone',
        'reference_notes',

        // Location / Routing
        'region',
        'area',
        'route',
        'beat',
        'territory',
        'zone',
        'sales_person',
        'warehouse',

        // KYC
        'aadhaar_last4',
        'kyc_completed',
        'kyc_verified_at',

        // Engagement
        'first_purchase_at',
        'last_purchase_at',
        'orders_count',

        // Status
        'is_active',
        'is_blacklisted',
        'internal_notes',

        // Audit
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'primary_crops'       => 'array',
        'secondary_crops'     => 'array',
        'credit_limit'        => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'annual_turnover'     => 'decimal:2',
        'kyc_completed'       => 'boolean',
        'is_active'           => 'boolean',
        'is_blacklisted'      => 'boolean',
        'credit_valid_till'   => 'date',
        'first_purchase_at'   => 'date',
        'last_purchase_at'    => 'date',
        'kyc_verified_at'     => 'datetime',
    ];

    /**
     * Accessor: Full name
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Creator (Admin/User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updater (Admin/User)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Enterprise: Multiple Addresses
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Default Billing Address
     */
    public function defaultBillingAddress()
    {
        return $this->hasOne(CustomerAddress::class)
            ->whereIn('type', ['billing', 'both'])
            ->where('is_default', true);
    }

    /**
     * Default Shipping Address
     */
    public function defaultShippingAddress()
    {
        return $this->hasOne(CustomerAddress::class)
            ->whereIn('type', ['shipping', 'both'])
            ->where('is_default', true);
    }
}
