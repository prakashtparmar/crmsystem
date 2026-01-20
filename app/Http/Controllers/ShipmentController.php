<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'carrier'          => 'nullable|string|max:255',
            'tracking_number'  => 'nullable|string|max:255',
            'shipped_at'       => 'nullable|date',
        ]);

        DB::transaction(function () use ($order, $data) {

            // Prevent duplicate active shipment
            if ($order->shipments()->whereIn('status', ['pending', 'shipped'])->exists()) {
                throw ValidationException::withMessages([
                    'shipment' => 'An active shipment already exists for this order.',
                ]);
            }

            $fromStatus = $order->status;

            // Create shipment
            $order->shipments()->create(array_merge($data, [
                'status'           => 'shipped',
                'address_snapshot' => $order->shipping_address,
            ]));

            // Update order status if not already shipped/delivered
            if (!in_array($order->status, ['shipped', 'delivered'])) {
                $order->update([
                    'status'     => 'shipped',
                    'updated_by' => auth()->id(),
                ]);

                // Optional: log status change
                OrderStatusLog::create([
                    'order_id'    => $order->id,
                    'from_status' => $fromStatus,
                    'to_status'   => 'shipped',
                    'changed_by'  => auth()->id(),
                    'changed_at'  => now(),
                ]);
            }
        });

        return back()->with('success', 'Shipment created successfully.');
    }
}
