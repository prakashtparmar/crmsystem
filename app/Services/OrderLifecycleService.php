<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderLifecycleService
{
    protected array $transitions = [
        'draft' => ['confirmed', 'cancelled'],
        'confirmed' => ['processing', 'shipped', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function changeStatus(
        Order $order,
        string $to,
        ?string $remarks = null,
        ?int $userId = null,
        InventoryService $inventory
    ) {
        DB::transaction(function () use ($order, $to, $remarks, $userId, $inventory) {

            $from = $order->status;

            // Idempotent
            if ($from === $to) {
                return;
            }

            // Load relations once
            $order->loadMissing('items', 'shipments', 'invoice');

            /**
             * ENTERPRISE BUSINESS RULES (no impact on existing inventory logic)
             */

            // Invoice required before shipping or delivering
            if (in_array($to, ['shipped', 'delivered'], true) && !$order->invoice) {
                throw ValidationException::withMessages([
                    'status' => 'Generate invoice before shipping or delivering this order.',
                ]);
            }

            // Shipping requires shipment record
            if ($to === 'shipped' && $order->shipments->isEmpty()) {
                throw ValidationException::withMessages([
                    'status' => 'Create shipment details before marking order as shipped.',
                ]);
            }

            // Delivery requires a shipped shipment
            if ($to === 'delivered') {
                $hasShipped = $order->shipments->contains(
                    fn($s) => $s->status === 'shipped'
                );

                if (!$hasShipped) {
                    throw ValidationException::withMessages([
                        'status' => 'Order must have a shipped shipment before delivery.',
                    ]);
                }
            }

            // Validate transition (unchanged)
            if (!in_array($to, $this->transitions[$from] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => "Invalid status transition: {$from} → {$to}",
                ]);
            }

            /**
             * INVENTORY RULES (unchanged)
             */

            // draft → confirmed : RESERVE
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

            // cancelled : RELEASE (only if not shipped)
            if ($to === 'cancelled' && $from !== 'shipped') {
                foreach ($order->items as $item) {
                    $inventory->releaseFromAnyWarehouse(
                        productId: $item->product_id,
                        qty: $item->quantity,
                        referenceType: 'order',
                        referenceId: $order->id
                    );
                }
            }

            // shipped : RESERVED → OUT
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

            // delivered : close shipment
            if ($to === 'delivered' && $from !== 'delivered') {
                $shipment = $order->shipments()
                    ->whereIn('status', ['pending', 'shipped'])
                    ->latest()
                    ->first();

                if ($shipment) {
                    $shipment->update([
                        'status' => 'delivered',
                        'delivered_at' => now(),
                    ]);
                }
            }

            // // Update order
            // $order->update([
            //     'status'       => $to,
            //     'completed_at' => $to === 'delivered' ? now() : null,
            // ]);

            $order->update([
    'status'     => $to,
    'updated_by' => auth()->id(),

    // Set once, never clear
    'confirmed_at' => $to === 'confirmed'
        ? now()
        : $order->confirmed_at,

    'cancelled_at' => $to === 'cancelled'
        ? now()
        : $order->cancelled_at,

    'completed_at' => $to === 'delivered'
        ? now()
        : $order->completed_at,
]);



            // Log
            OrderStatusLog::create([
                'order_id' => $order->id,
                'from_status' => $from,
                'to_status' => $to,
                'remarks' => $remarks,
                'changed_by' => $userId,
                'changed_at' => now(),
            ]);
        });
    }
}
