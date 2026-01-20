<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Support\Facades\DB;

class OrderLifecycleService
{
    public function changeStatus(Order $order, string $to, ?string $remarks = null, ?int $userId = null)
    {
        DB::transaction(function () use ($order, $to, $remarks, $userId) {
            $from = $order->status;

            $order->update([
                'status' => $to,
                'completed_at' => $to === 'delivered' ? now() : null,
            ]);

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'from_status'=> $from,
                'to_status'  => $to,
                'remarks'    => $remarks,
                'changed_by' => $userId,
                'changed_at' => now(),
            ]);
        });
    }
}
