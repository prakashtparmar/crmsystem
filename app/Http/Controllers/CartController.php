<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['customer_id' => auth()->user()->id, 'status' => 'active'],
                ['session_id' => session()->getId()]
            );
        }

        return Cart::firstOrCreate(
            ['session_id' => session()->getId(), 'status' => 'active']
        );
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + 1,
                'total' => ($item->quantity + 1) * $item->price,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'total' => $product->price,
            ]);
        }

        $this->recalculate($cart);

        return back()->with('success', 'Added to cart.');
    }

    public function view()
    {
        $cart = $this->getCart()->load('items');
        return view('shop.cart', compact('cart'));
    }

    protected function recalculate(Cart $cart)
    {
        $sub = $cart->items()->sum('total');
        $cart->update([
            'sub_total' => $sub,
            'grand_total' => $sub,
        ]);
    }
}
