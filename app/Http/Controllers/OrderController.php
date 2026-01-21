<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')
            ->latest()
            ->paginate(20);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();

        // 🔧 Enhanced: load products with available stock
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
                'products.id',
                'products.name',
                'products.price',
                'products.gst_percent',
                DB::raw('CAST(COALESCE(stock_totals.available_qty, 0) AS UNSIGNED) as available_qty')
            )
            ->where('products.is_active', true)
            ->orderBy('products.name')
            ->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request, InventoryService $inventory)
{
    // 🔧 FIX: remove empty rows added by "Add Item"
    $items = array_values(array_filter(
        $request->input('items', []),
        fn ($row) => !empty($row['product_id']) && !empty($row['qty'])
    ));

    $request->merge(['items' => $items]);

    $data = $request->validate([
        'customer_id'           => 'required|exists:customers,id',
        'billing_address_id'    => 'nullable|exists:customer_addresses,id',
        'shipping_address_id'   => 'nullable|exists:customer_addresses,id',
        'items'                 => 'required|array|min:1',
        'items.*.product_id'    => 'required|exists:products,id',
        'items.*.qty'           => 'required|numeric|min:1',
    ]);

    try {
        $order = DB::transaction(function () use ($data, $inventory, $request) {

            $customer = Customer::with(['addresses'])
                ->findOrFail($data['customer_id']);

            /**
             * 🆕 Create NEW BILLING address if provided
             */
            if ($request->filled('new_billing.line1')) {
                $addr = $customer->addresses()->create([
                    'customer_id'   => $customer->id,
                    'type'          => 'billing',
                    'address_line1' => $request->input('new_billing.line1'),
                    'address_line2' => $request->input('new_billing.line2'),
                    'district'      => $request->input('new_billing.city'),
                    'state'         => $request->input('new_billing.state'),
                    'pincode'       => $request->input('new_billing.pincode'),
                    'country'       => 'India',
                ]);

                $data['billing_address_id'] = $addr->id;
            }

            /**
             * 🆕 Create NEW SHIPPING address if provided
             */
            if ($request->filled('new_shipping.line1')) {
                $addr = $customer->addresses()->create([
                    'customer_id'   => $customer->id,
                    'type'          => 'shipping',
                    'address_line1' => $request->input('new_shipping.line1'),
                    'address_line2' => $request->input('new_shipping.line2'),
                    'district'      => $request->input('new_shipping.city'),
                    'state'         => $request->input('new_shipping.state'),
                    'pincode'       => $request->input('new_shipping.pincode'),
                    'country'       => 'India',
                ]);

                $data['shipping_address_id'] = $addr->id;
            }

            // refresh addresses
            $customer->load('addresses');

            /**
             * 🔁 Same-as-billing logic (works for existing & new)
             */
            if (
                $request->boolean('same_as_billing') &&
                !empty($data['billing_address_id']) &&
                empty($data['shipping_address_id'])
            ) {
                $data['shipping_address_id'] = $data['billing_address_id'];
            }

            $billing = isset($data['billing_address_id'])
                ? $customer->addresses->firstWhere('id', $data['billing_address_id'])
                : $customer->defaultBillingAddress;

            $shipping = isset($data['shipping_address_id'])
                ? $customer->addresses->firstWhere('id', $data['shipping_address_id'])
                : $customer->defaultShippingAddress;

            $order = Order::create([
                'uuid'             => (string) Str::uuid(),
                'order_code'       => 'ORD-' . now()->format('Ymd-His'),
                'customer_id'      => $customer->id,
                'customer_name'    => $customer->display_name
                    ?? trim($customer->first_name . ' ' . $customer->last_name),
                'customer_email'   => $customer->email,
                'customer_phone'   => $customer->mobile,
                'order_date'       => now(),
                'status'           => 'draft',
                'payment_status'   => 'unpaid',
                'billing_address'  => $billing ? $billing->formatted() : null,
                'shipping_address' => $shipping ? $shipping->formatted() : null,
                'created_by'       => auth()->id(),
            ]);

            $subTotal = 0;
            $taxTotal = 0;

            foreach ($data['items'] as $row) {
                $product = Product::findOrFail($row['product_id']);

                $price = $product->price;
                $qty   = $row['qty'];

                $line    = $price * $qty;
                $taxRate = $product->gst_percent ?? 0;
                $lineTax = ($line * $taxRate) / 100;

                $order->items()->create([
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'tax_rate'     => $taxRate,
                    'tax_amount'   => $lineTax,
                    'total'        => $line + $lineTax,
                ]);

                $subTotal += $line;
                $taxTotal += $lineTax;
            }

            $order->update([
                'sub_total'   => $subTotal,
                'tax_amount'  => $taxTotal,
                'grand_total' => $subTotal + $taxTotal,
            ]);

            return $order;
        });

        $fromCustomer = str_contains(url()->previous(), '/customers/');

return $fromCustomer
    ? redirect()
        ->route('customers.show', $order->customer_id)
        ->with('success', 'Order placed successfully.')
    : redirect()
        ->route('orders.index')
        ->with('success', 'Order placed successfully.');

    } catch (\Throwable $e) {
        Log::error('Order create failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->with('error', 'Failed to place order. Please try again.');
    }
}


    public function show(Order $order)
    {
        $order->load('items', 'customer', 'payments', 'shipments', 'statusLogs', 'invoice');

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items');

        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->only([
            'billing_address',
            'shipping_address',
        ]));

        return back()->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted.');
    }
}
