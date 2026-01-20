<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'product_id'   => 'required|integer',
            'product_name' => 'required|string',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|numeric|min:0.01',
            'tax_rate'     => 'nullable|numeric|min:0',
        ]);

        $line = $data['price'] * $data['quantity'];
        $taxRate = $data['tax_rate'] ?? 0;
        $tax = ($line * $taxRate) / 100;

        $data['tax_amount'] = $tax;
        $data['total'] = $line + $tax;

        $order->items()->create($data);

        $this->recalculate($order);

        return back()->with('success', 'Item added.');
    }

    public function destroy(Order $order, $itemId)
    {
        $order->items()->where('id', $itemId)->delete();
        $this->recalculate($order);

        return back()->with('success', 'Item removed.');
    }

    protected function recalculate(Order $order)
    {
        $sub = $order->items()->sum('price * quantity');
        $tax = $order->items()->sum('tax_amount');

        $order->update([
            'sub_total'   => $sub,
            'tax_amount'  => $tax,
            'grand_total' => $sub + $tax,
        ]);
    }
}
