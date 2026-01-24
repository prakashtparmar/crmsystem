<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers.view')->only(['addresses']);
    }

    /**
     * URL: /customers/{customer}/addresses
     */
    public function addresses(Customer $customer)
    {
        return $customer->addresses()
            ->orderByDesc('is_default')
            ->get()
            ->flatMap(function ($addr) {

                $formatted = implode(', ', array_filter([
                    $addr->label,
                    $addr->contact_name,
                    $addr->address_line1,
                    $addr->address_line2,
                    $addr->village,
                    $addr->district,
                    $addr->state,
                    $addr->pincode,
                    $addr->country,
                ]));

                if ($addr->type === 'both') {
                    return [
                        [
                            'id'        => $addr->id,
                            'type'      => 'billing',
                            'formatted' => $formatted,
                        ],
                        [
                            'id'        => $addr->id,
                            'type'      => 'shipping',
                            'formatted' => $formatted,
                        ],
                    ];
                }

                return [[
                    'id'        => $addr->id,
                    'type'      => $addr->type,
                    'formatted' => $formatted,
                ]];
            })
            ->values();
    }
}
