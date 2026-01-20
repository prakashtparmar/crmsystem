<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function place()
    {
        $cart = Cart::where('status','active')
            ->where('session_id', session()->getId())
            ->with('items')
            ->firstOrFail();

        DB::transaction(function () use ($cart) {

            $next = (Order::lockForUpdate()->max('id') ?? 0) + 1;

            $order = Order::create([
                'uuid' => (string) Str::uuid(),
                'order_code' => 'ORD-' . str_pad($next, 6, '0', STR_PAD_LEFT),
                'customer_id' => $cart->customer_id,
                'customer_name' => auth()->user()->name ?? 'Guest',
                'customer_phone' => auth()->user()->mobile ?? null,
                'order_date' => now(),
                'status' => 'confirmed',
                'sub_total' => $cart->sub_total,
                'grand_total' => $cart->grand_total,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                ]);
            }

            $cart->update(['status' => 'converted']);
        });

        return redirect()->route('orders.index')->with('success','Order placed.');
    }
}
