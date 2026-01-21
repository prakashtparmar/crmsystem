<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'carrier'         => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'shipped_at'      => 'nullable|date',
        ]);

        DB::transaction(function () use ($order, $data) {

            // Prevent duplicate active shipment
            if ($order->shipments()->whereIn('status', ['pending', 'shipped'])->exists()) {
                throw ValidationException::withMessages([
                    'shipment' => 'An active shipment already exists for this order.',
                ]);
            }

            // Only create shipment record
            $order->shipments()->create(array_merge($data, [
                'status'           => 'pending', // status of shipment, not order
                'address_snapshot' => $order->shipping_address,
            ]));
        });

        return back()->with('success', 'Shipment created successfully.');
    }
}
