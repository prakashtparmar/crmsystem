<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends Controller
{
    public function index(Request $request)
    {
        $showTrashed = (bool) $request->get('trash', false);

        $query = OrderReturn::with(['order', 'items.orderItem'])->latest();

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $returns = $query->paginate(20);

        return view('order-returns.index', compact('returns', 'showTrashed'));
    }

    public function create(Request $request)
    {
        $order = Order::with('items')->findOrFail($request->order_id);
        return view('order-returns.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'items'    => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $return = OrderReturn::create([
                'order_id'      => $request->order_id,
                'user_id'       => auth()->id(),
                'return_number' => 'RET-' . now()->format('YmdHis'),
                'reason'        => $request->reason,
                'status'        => 'pending',
            ]);

            $refund = 0;

            foreach ($request->items as $itemId => $qty) {
                if ($qty <= 0) {
                    continue;
                }

                $item = OrderItem::findOrFail($itemId);
                $amount = $item->price * $qty;

                $return->items()->create([
                    'order_item_id' => $itemId,
                    'qty'           => $qty,
                    'amount'        => $amount,
                ]);

                $refund += $amount;
            }

            $return->update(['refund_amount' => $refund]);
        });

        return redirect()
            ->route('order-returns.index')
            ->with('success', 'Order return created successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids'    => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'approve':
                OrderReturn::whereIn('id', $ids)
                    ->where('status', 'pending')
                    ->update(['status' => 'approved']);
                break;

            case 'reject':
                OrderReturn::whereIn('id', $ids)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
                break;

            case 'delete':
                OrderReturn::whereIn('id', $ids)->delete();
                break;

            case 'restore':
                OrderReturn::onlyTrashed()->whereIn('id', $ids)->restore();
                break;
        }

        return back()->with('success', 'Bulk action applied successfully.');
    }

    public function approve(OrderReturn $orderReturn)
    {
        if ($orderReturn->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be approved.');
        }

        $orderReturn->update(['status' => 'approved']);

        return back()->with('success', 'Return approved.');
    }

    public function reject(OrderReturn $orderReturn)
    {
        if ($orderReturn->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be rejected.');
        }

        $orderReturn->update(['status' => 'rejected']);

        return back()->with('success', 'Return rejected.');
    }

    public function refund(OrderReturn $orderReturn)
    {
        if ($orderReturn->status !== 'approved') {
            return back()->with('error', 'Only approved returns can be refunded.');
        }

        DB::transaction(function () use ($orderReturn) {

            $order = $orderReturn->order;

            $order->payments()->create([
                'method'         => 'refund',
                'amount'         => -$orderReturn->refund_amount,
                'paid_at'        => now(),
                'reference'      => 'Return #' . $orderReturn->return_number,
                'balance_after' => $order->balance + $orderReturn->refund_amount,
            ]);

            $order->increment('balance', $orderReturn->refund_amount);

            $orderReturn->update(['status' => 'refunded']);
        });

        return back()->with('success', 'Refund processed successfully.');
    }

    public function edit(OrderReturn $orderReturn)
    {
        $orderReturn->load('order', 'items.orderItem');

        return view('order-returns.edit', [
            'return' => $orderReturn, // important for Blade
        ]);
    }

    public function update(Request $request, OrderReturn $orderReturn)
    {
        // convert action -> status if needed
        $status = $request->input('status') ?? $request->input('action');
        $request->merge(['status' => $status]);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,refunded',
        ]);

        if ($orderReturn->status === 'refunded') {
            return back()->with('error', 'Refunded returns cannot be modified.');
        }

        if ($request->status === 'approved' && $orderReturn->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be approved.');
        }

        if ($request->status === 'rejected' && $orderReturn->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be rejected.');
        }

        $orderReturn->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('order-returns.index')
            ->with('success', 'Return updated successfully.');
    }
}
