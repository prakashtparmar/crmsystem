<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;

class CustomerController extends Controller
{
    public function __construct()
{
    // Viewing customers & details
    $this->middleware('permission:customers.view')->only(['index', 'show', 'addresses']);

    // Searching customers (separate permission)
    $this->middleware('permission:customers.search')->only(['search']);

    // Create
    $this->middleware('permission:customers.create')->only(['create', 'store']);

    // Edit
    $this->middleware('permission:customers.edit')->only(['edit', 'update']);

    // Delete
    $this->middleware('permission:customers.delete')->only(['destroy']);
}



    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data) {
            $lastId = Customer::lockForUpdate()->max('id') ?? 0;
            $customerCode = 'CUST-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);

            Customer::create(array_merge($data, [
                'uuid'          => (string) Str::uuid(),
                'customer_code' => $customerCode,
                'created_by'    => auth()->id(),
            ]));
        });

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $orders = $customer->orders()
            ->latest()
            ->limit(10)
            ->get();

        $products = Product::query()
            ->leftJoinSub(
                DB::table('product_stocks')
                    ->selectRaw('product_id, SUM(quantity - reserved_qty) as available_qty')
                    ->groupBy('product_id'),
                'stock_totals',
                'products.id',
                '=',
                'stock_totals.product_id'
            )
            ->select(
                'products.*',
                DB::raw('COALESCE(stock_totals.available_qty, 0) as available_qty')
            )
            ->orderBy('products.name')
            ->get();

        return view('customers.show', compact('customer', 'orders', 'products'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $this->validatedData($request, $customer->id);

        $customer->update(array_merge($data, [
            'updated_by' => auth()->id(),
        ]));

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * 🔹 ENTERPRISE: Return customer addresses for Order UI
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

            // If address is for both, return two records
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

    protected function validatedData(Request $request, ?int $customerId = null): array
    {
        $rules = [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'mobile'       => 'required|string|max:20|unique:customers,mobile,' . $customerId,
            'email'        => 'nullable|email|max:255',

            'type'     => 'required|in:farmer,buyer,vendor,dealer',
            'category' => 'required|in:individual,business',

            'company_name' => 'nullable|string|max:255',
            'gst_number'   => 'nullable|string|max:50',
            'pan_number'   => 'nullable|string|max:50',

            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'village'       => 'nullable|string|max:255',
            'taluka'        => 'nullable|string|max:255',
            'district'      => 'nullable|string|max:255',
            'state'         => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:255',
            'pincode'       => 'nullable|string|max:10',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',

            'land_area'       => 'nullable|numeric',
            'land_unit'       => 'nullable|string|max:50',
            'primary_crops'   => 'nullable',
            'secondary_crops' => 'nullable',
            'irrigation_type' => 'nullable|string|max:255',

            'credit_limit'        => 'nullable|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
            'credit_valid_till'   => 'nullable|date',

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

        $data['is_active']      = $request->boolean('is_active', true);
        $data['is_blacklisted'] = $request->boolean('is_blacklisted');
        $data['kyc_completed']  = $request->boolean('kyc_completed');

        if ($data['kyc_completed']) {
            $data['kyc_verified_at'] = now();
        } else {
            $data['kyc_verified_at'] = null;
        }

        return $data;
    }

    public function search(Request $request)
    {
        $mobile = trim($request->input('mobile'));

        if (!$mobile) {
            return redirect()
                ->back()
                ->with('error', 'Please enter a mobile number to search.');
        }

        $customer = Customer::where('mobile', 'like', '%' . $mobile . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$customer) {
            return redirect()
                ->route('customers.create', ['mobile' => $mobile])
                ->with('error', 'No customer found. Please create a new customer.');
        }

        return redirect()->route('customers.show', $customer);
    }
}
