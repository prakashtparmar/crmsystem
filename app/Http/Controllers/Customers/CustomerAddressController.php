<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers.view')->only(['addresses']);
        $this->middleware('permission:customers.edit')->only(['store']);
    }

    /**
     * GET: /customers/{customer}/addresses
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

    /**
     * POST: /customers/{customer}/addresses
     */
    public function store(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'type'          => 'required|in:billing,shipping',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'pincode'       => 'nullable|string',
            'post_office'   => 'nullable|string',
            'village'       => 'nullable|string',
            'taluka'        => 'nullable|string',
            'district'      => 'nullable|string',
            'state'         => 'nullable|string',
        ]);

        $addr = $customer->addresses()->create($data);

        return response()->json([
            'id'   => $addr->id,
            'text' => $addr->address_line1 . ', ' . $addr->district,
        ]);
    }
}
