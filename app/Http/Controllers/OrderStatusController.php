<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStatusController extends Controller
{
    /**
     * Allowed state transitions
     */
    protected array $transitions = [
        'draft'      => ['confirmed', 'cancelled'],
        'confirmed'  => ['processing', 'shipped', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped'    => ['delivered'],
        'delivered'  => [],
        'cancelled'  => [],
    ];

    public function update(Request $request, Order $order, InventoryService $inventory)
    {
        $data = $request->validate([
            'status'  => 'required|string',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $from = $order->status;
        $to   = $data['status'];

        // Idempotent: no-op if same status
        if ($from === $to) {
            return back()->with('info', 'Order is already in this status.');
        }

        // Validate transition
        if (! in_array($to, $this->transitions[$from] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => "Invalid status transition: {$from} → {$to}",
            ]);
        }

        DB::transaction(function () use ($order, $from, $to, $data, $inventory) {

            // Load relations once
            $order->loadMissing('items', 'shipments');

            /**
             * BUSINESS RULES
             *
             * draft      -> confirmed : RESERVE stock
             * confirmed  -> shipped   : convert reserve to OUT
             * any        -> cancelled : RELEASE reserved (only if not shipped)
             * shipped    -> delivered : close shipment
             */

            // draft -> confirmed : reserve stock
            if ($to === 'confirmed' && $from === 'draft') {
                foreach ($order->items as $item) {
                    $inventory->reserveFromAnyWarehouse(
                        productId: $item->product_id,
                        qty: $item->quantity,
                        referenceType: 'order',
                        referenceId: $order->id
                    );
                }
            }

            // Cancel: release only if not already shipped
            if ($to === 'cancelled' && $from !== 'cancelled' && $from !== 'shipped') {
                foreach ($order->items as $item) {
                    $inventory->releaseFromAnyWarehouse(
                        productId: $item->product_id,
                        qty: $item->quantity,
                        referenceType: 'order',
                        referenceId: $order->id
                    );
                }
            }

            // Ship: convert reserved → out (physical stock decreases)
            if ($to === 'shipped' && $from !== 'shipped') {
                foreach ($order->items as $item) {
                    $inventory->shipFromReserved(
                        productId: $item->product_id,
                        qty: $item->quantity,
                        referenceType: 'order',
                        referenceId: $order->id
                    );
                }
            }

            // When delivered, update shipment
            if ($to === 'delivered' && $from !== 'delivered') {
                $shipment = $order->shipments()
                    ->whereIn('status', ['pending', 'shipped'])
                    ->latest()
                    ->first();

                if ($shipment) {
                    $shipment->update([
                        'status'           => 'delivered',
                        'delivered_at'     => now(),
                        'address_snapshot' => $shipment->address_snapshot
                            ?: $order->shipping_address,
                    ]);
                }
            }

            // Update order
            $order->update([
                'status'       => $to,
                'updated_by'   => auth()->id(),
                'completed_at' => $to === 'delivered' ? now() : $order->completed_at,
            ]);

            // Log status change
            OrderStatusLog::create([
                'order_id'    => $order->id,
                'from_status' => $from,
                'to_status'   => $to,
                'remarks'     => $data['remarks'] ?? null,
                'changed_by'  => auth()->id(),
                'changed_at'  => now(),
            ]);
        });

        return back()->with('success', "Order moved from {$from} to {$to}.");
    }
}
