<?php

namespace App\Http\Controllers\Customers\Concerns;

use Illuminate\Http\Request;

trait CustomerValidation
{
    protected function validatedCustomerData(Request $request, ?int $customerId = null): array
    {
        $rules = [
            /* ===== Core Identity (Required) ===== */
            'first_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile,' . $customerId,

            /* ===== Optional Identity ===== */
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',


            /* ===== Classification ===== */
            'type' => 'sometimes|required|in:farmer,buyer,vendor,dealer',
            'category' => 'sometimes|required|in:individual,business',

            /* ===== Business ===== */
            'company_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'business_type' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'established_year' => 'nullable|digits:4',
            'annual_turnover' => 'nullable|numeric',

            /* ===== Address ===== */
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'taluka' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:6',
            'post_office' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            /* ===== Agriculture ===== */
            'land_area' => 'nullable|regex:/^\d+(\.\d+)?$/',
            'land_unit' => 'nullable|string|max:50',
            'primary_crops' => 'nullable',
            'secondary_crops' => 'nullable',
            'irrigation_type' => 'nullable|string|max:255',
            'farming_method' => 'nullable|string|max:255',
            'soil_type' => 'nullable|string|max:255',

            /* ===== Financial ===== */
            'credit_limit' => 'nullable|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
            'credit_valid_till' => 'nullable|date',

            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'preferred_payment_mode' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'billing_cycle' => 'nullable|string|max:100',

            /* ===== Contacts ===== */
            'phone_number_2' => 'nullable|string|max:20',
            'secondary_contact_name' => 'nullable|string|max:255',
            'secondary_contact_phone' => 'nullable|string|max:20',
            'relative_phone' => 'nullable|string|max:20',

            /* ===== Location / Sales ===== */
            'area' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'territory' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'beat' => 'nullable|string|max:255',
            'warehouse' => 'nullable|string|max:255',
            'sales_person' => 'nullable|string|max:255',

            /* ===== References ===== */
            'referred_by' => 'nullable|string|max:255',
            'reference_name' => 'nullable|string|max:255',
            'reference_phone' => 'nullable|string|max:20',
            'reference_type' => 'nullable|string|max:100',
            'reference_notes' => 'nullable|string',

            /* ===== Compliance ===== */
            'aadhaar_last4' => 'nullable|string|max:4',
            'internal_notes' => 'nullable|string',
        ];

        $data = $request->validate($rules);

        foreach (['primary_crops', 'secondary_crops'] as $field) {
            if (!empty($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                $data[$field] = json_last_error() === JSON_ERROR_NONE
                    ? $decoded
                    : array_map('trim', explode(',', $data[$field]));
            }
        }

        // Defaults / flags
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_blacklisted'] = $request->boolean('is_blacklisted');
        $data['kyc_completed'] = $request->boolean('kyc_completed');

        // System-controlled KYC timestamp
        $data['kyc_verified_at'] = $data['kyc_completed'] ? now() : null;

        return $data;
    }
}
