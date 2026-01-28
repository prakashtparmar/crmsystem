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
    public function __construct()
    {
        $this->middleware('permission:orders.view|orders.view_all|orders.view_own')->only(['index', 'show']);
        $this->middleware('permission:orders.create')->only(['create', 'store']);
        $this->middleware('permission:orders.edit')->only(['edit', 'update']);
        $this->middleware('permission:orders.delete')->only(['destroy']);
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Master Admin');

        if (! $isSuperAdmin) {
            abort_unless(
                $user->can('orders.view') ||
                $user->can('orders.view_all') ||
                $user->can('orders.view_own'),
                403
            );
        }

        $orders = Order::with(['customer', 'creator', 'invoice', 'shipments'])
            ->withSum('payments as total_paid', 'amount')
            ->when(
                ! $isSuperAdmin && ! $user->can('orders.view_all'),
                fn ($q) => $q->where('created_by', $user->id)
            )
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();

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
    foreach (['billing_address_id', 'shipping_address_id'] as $key) {
        if ($request->input($key) === 'new') {
            $request->merge([$key => null]);
        }
    }

    $items = array_values(array_filter(
        $request->input('items', []),
        fn ($row) => !empty($row['product_id']) && !empty($row['qty'])
    ));

    $request->merge(['items' => $items]);

    $data = $request->validate([
        'customer_id'         => 'required|exists:customers,id',
        'billing_address_id'  => 'nullable|exists:customer_addresses,id',
        'shipping_address_id' => 'nullable|exists:customer_addresses,id',
        'items'               => 'required|array|min:1',
        'items.*.product_id'  => 'required|exists:products,id',
        'items.*.qty'         => 'required|numeric|min:1',
        'discount_amount'     => 'nullable|numeric|min:0',
    ]);

    try {
        DB::transaction(function () use ($data, $request) {

            $customer = Customer::with(['addresses'])->findOrFail($data['customer_id']);

            // BILLING ADDRESS
            if ($request->filled('new_billing.address_line1')) {
                $addr = $customer->addresses()->create([
                    'customer_id'   => $customer->id,
                    'type'          => 'billing',
                    'is_default'    => true,
                    'label'         => $request->input('new_billing.label'),
                    'contact_name'  => $request->input('new_billing.contact_name'),
                    'contact_phone' => $request->input('new_billing.contact_phone'),

                    'address_line1' => $request->input('new_billing.address_line1'),
                    'address_line2' => $request->input('new_billing.address_line2'),
                    'village'       => $request->input('new_billing.village'),
                    'taluka'        => $request->input('new_billing.taluka'),
                    'district'      => $request->input('new_billing.district'),
                    'state'         => $request->input('new_billing.state'),
                    'pincode'       => $request->input('new_billing.pincode'),
                    'post_office'   => $request->input('new_billing.post_office'),
                    'country'       => 'India',

                    'region'        => $request->input('new_billing.region'),
                    'area'          => $request->input('new_billing.area'),
                    'route'         => $request->input('new_billing.route'),
                    'beat'          => $request->input('new_billing.beat'),
                    'territory'     => $request->input('new_billing.territory'),
                    'zone'          => $request->input('new_billing.zone'),
                    'warehouse'     => $request->input('new_billing.warehouse'),
                    'latitude'      => $request->input('new_billing.latitude'),
                    'longitude'     => $request->input('new_billing.longitude'),
                ]);

                $data['billing_address_id'] = $addr->id;
            }

            // SHIPPING ADDRESS
            if ($request->filled('new_shipping.address_line1')) {
                $addr = $customer->addresses()->create([
                    'customer_id'   => $customer->id,
                    'type'          => 'shipping',
                    'is_default'    => true,
                    'label'         => $request->input('new_shipping.label'),
                    'contact_name'  => $request->input('new_shipping.contact_name'),
                    'contact_phone' => $request->input('new_shipping.contact_phone'),

                    'address_line1' => $request->input('new_shipping.address_line1'),
                    'address_line2' => $request->input('new_shipping.address_line2'),
                    'village'       => $request->input('new_shipping.village'),
                    'taluka'        => $request->input('new_shipping.taluka'),
                    'district'      => $request->input('new_shipping.district'),
                    'state'         => $request->input('new_shipping.state'),
                    'pincode'       => $request->input('new_shipping.pincode'),
                    'post_office'   => $request->input('new_shipping.post_office'),
                    'country'       => 'India',

                    'region'        => $request->input('new_shipping.region'),
                    'area'          => $request->input('new_shipping.area'),
                    'route'         => $request->input('new_shipping.route'),
                    'beat'          => $request->input('new_shipping.beat'),
                    'territory'     => $request->input('new_shipping.territory'),
                    'zone'          => $request->input('new_shipping.zone'),
                    'warehouse'     => $request->input('new_shipping.warehouse'),
                    'latitude'      => $request->input('new_shipping.latitude'),
                    'longitude'     => $request->input('new_shipping.longitude'),
                ]);

                $data['shipping_address_id'] = $addr->id;
            }

            if (
                $request->boolean('same_as_billing') &&
                !empty($data['billing_address_id']) &&
                empty($data['shipping_address_id'])
            ) {
                $data['shipping_address_id'] = $data['billing_address_id'];
            }

            $customer->load('addresses');

            $billingText = !empty($data['billing_address_id'])
                ? optional($customer->addresses->firstWhere('id', $data['billing_address_id']))->formatted()
                : null;

            $shippingText = !empty($data['shipping_address_id'])
                ? optional($customer->addresses->firstWhere('id', $data['shipping_address_id']))->formatted()
                : ($request->boolean('same_as_billing') ? $billingText : null);

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
                'billing_address'  => $billingText,
                'shipping_address' => $shippingText,
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

            $rawDiscount = (float) ($request->input('discount_amount') ?? 0);
            $maxAllowed  = $subTotal + $taxTotal;
            $discount    = min($rawDiscount, $maxAllowed);

            $order->update([
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxTotal,
                'discount_amount' => $discount,
                'grand_total'     => round($maxAllowed - $discount, 2),
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');

    } catch (\Throwable $e) {
        Log::error('Order create failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to place order. Please try again.');
    }
}



    public function show(Order $order)
    {
        $user = auth()->user();

        if ($user->can('orders.view_own') && ! $user->can('orders.view_all')) {
            abort_unless($order->created_by === $user->id, 403);
        }

        $order->load('items', 'customer', 'payments', 'shipments', 'statusLogs', 'invoice');

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $user = auth()->user();

        if ($user->can('orders.view_own') && ! $user->can('orders.view_all')) {
            abort_unless($order->created_by === $user->id, 403);
        }

        $order->load('items');

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

        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
{
    $user = auth()->user();

    if ($user->can('orders.view_own') && ! $user->can('orders.view_all')) {
        abort_unless($order->created_by === $user->id, 403);
    }


    $items = array_values(array_filter(
        $request->input('items', []),
        fn ($row) => !empty($row['product_id']) && !empty($row['qty'])
    ));

    $request->merge(['items' => $items]);

    $data = $request->validate([
        'billing_address'    => 'nullable|string',
        'shipping_address'   => 'nullable|string',
        'items'              => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty'        => 'required|numeric|min:1',
        'discount_amount'    => 'nullable|numeric|min:0',
    ]);

    try {
        DB::transaction(function () use ($order, $data, $request) {

            $order->update([
                'billing_address'  => $data['billing_address'] ?? $order->billing_address,
                'shipping_address' => $data['shipping_address'] ?? $order->shipping_address,
            ]);

            $order->items()->delete();

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

            // Clamp discount so it never exceeds payable amount
            $rawDiscount = (float) ($request->input('discount_amount') ?? 0);
            $maxAllowed  = $subTotal + $taxTotal;
            $discount    = min($rawDiscount, $maxAllowed);

            $order->update([
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxTotal,
                'discount_amount' => $discount,
                'grand_total'     => round($maxAllowed - $discount, 2),
            ]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');

    } catch (\Throwable $e) {
        Log::error('Order update failed', [
            'order_id' => $order->id,
            'error'    => $e->getMessage(),
        ]);

        return back()->with('error', 'Failed to update order.');
    }
}


    public function destroy(Order $order)
    {
        $user = auth()->user();

        if ($user->can('orders.view_own') && ! $user->can('orders.view_all')) {
            abort_unless($order->created_by === $user->id, 403);
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
