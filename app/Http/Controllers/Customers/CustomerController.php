<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Customers\Concerns\CustomerValidation;

class CustomerController extends Controller
{
    use CustomerValidation;

    public function __construct()
    {
        $this->middleware('permission:customers.view')->only(['index', 'show']);
        $this->middleware('permission:customers.create')->only(['create', 'store']);
        $this->middleware('permission:customers.edit')->only(['edit', 'update']);
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
        $customerData = $this->validatedCustomerData($request);
        $addressData  = $this->validatedAddressData($request);

        DB::transaction(function () use ($customerData, $addressData) {
            $lastId = Customer::lockForUpdate()->max('id') ?? 0;
            $customerCode = 'CUST-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);

            $customer = Customer::create(array_merge($customerData, [
                'uuid'          => (string) Str::uuid(),
                'customer_code' => $customerCode,
                'created_by'    => auth()->id(),
            ]));

            // Create default address
            if (!empty($addressData['address_line1'])) {
                $customer->addresses()->create(array_merge($addressData, [
                    'type'       => 'shipping',
                    'is_default' => true,
                ]));
            }
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
        $customer->load('addresses');
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $customerData = $this->validatedCustomerData($request, $customer->id);
        $addressData  = $this->validatedAddressData($request);

        DB::transaction(function () use ($customer, $customerData, $addressData) {
            $customer->update(array_merge($customerData, [
                'updated_by' => auth()->id(),
            ]));

            if (!empty($addressData['address_line1'])) {
                $addr = $customer->addresses()
                    ->where('is_default', true)
                    ->first();

                if ($addr) {
                    $addr->update($addressData);
                } else {
                    $customer->addresses()->create(array_merge($addressData, [
                        'type'       => 'shipping',
                        'is_default' => true,
                    ]));
                }
            }
        });

        if (request()->has('final_submit')) {
            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer updated successfully.');
        }

        return redirect()
            ->route('customers.edit', $customer)
            ->with('success', 'Customer details updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
