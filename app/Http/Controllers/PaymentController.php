<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    /**
     * Single order payment (UNCHANGED)
     */
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'method'    => 'required|in:cash,upi,bank_transfer,card,cheque',
            'amount'    => 'required|numeric|min:0.01',
            'paid_at'   => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($order, $data) {

            // Recalculate inside transaction for safety (only completed payments)
            $alreadyPaid = $order->payments()
                ->where('status', 'completed')
                ->sum('amount');

            $remaining = max(0, $order->grand_total - $alreadyPaid);

            if ($remaining <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'This order is already fully paid.',
                ]);
            }

            if ($data['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => "Payment exceeds remaining balance ({$remaining}).",
                ]);
            }

            $newBalance = $remaining - $data['amount'];

            // Create payment
            $order->payments()->create([
                'method'        => $data['method'],
                'amount'        => $data['amount'],
                'paid_at'       => $data['paid_at'],
                'reference'     => $data['reference'] ?? null,
                'status'        => 'completed',
                'received_by'   => auth()->id(),
                'balance_after' => $newBalance,
            ]);

            // Update order payment state
            $order->update([
                'payment_status' => $newBalance <= 0 ? 'paid' : 'partial',
                'payment_method' => $data['method'],
                'paid_at'        => $newBalance <= 0 ? now() : null,
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show bulk upload form
     */
    public function bulkForm()
    {
        return view('orders.bulk-payments');
    }

    /**
     * Process bulk payment file
     */
    public function bulkProcess(Request $request)
{
    $rows = $request->input('rows', []);

    if (empty($rows)) {
        return back()->withErrors('No rows selected to process.');
    }

    DB::transaction(function () use ($rows) {
        foreach ($rows as $row) {

            // Skip unchecked rows
            if (!isset($row['use'])) {
                continue;
            }

            $orderCode = trim($row['order_code'] ?? '');
            $amount    = (float) ($row['amount'] ?? 0);
            $method    = trim($row['method'] ?? '');
            $paidAt    = $row['paid_at'] ?? null;
            $reference = $row['reference'] ?? null;

            if (! $orderCode || ! $amount || ! $method || ! $paidAt) {
                continue;
            }

            $order = \App\Models\Order::where('order_code', $orderCode)->first();
            if (! $order) {
                continue;
            }

            // Same safety logic as single payment
            $alreadyPaid = $order->payments()
                ->where('status', 'completed')
                ->sum('amount');

            $remaining = max(0, $order->grand_total - $alreadyPaid);
            if ($remaining <= 0) {
                continue;
            }

            $payAmount = min($remaining, $amount);
            $newBalance = $remaining - $payAmount;

            $order->payments()->create([
                'method'        => $method,
                'amount'        => $payAmount,
                'paid_at'       => $paidAt,
                'reference'     => $reference,
                'status'        => 'completed',
                'received_by'   => auth()->id(),
                'balance_after' => $newBalance,
            ]);

            $order->update([
                'payment_status' => $newBalance <= 0 ? 'paid' : 'partial',
                'payment_method' => $method,
                'paid_at'        => $newBalance <= 0 ? now() : null,
            ]);
        }
    });

    return back()->with('success', 'Bulk payments processed successfully.');
}



}
