<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'method'    => 'required|in:cash,upi,bank_transfer,card,cheque',
            'amount'    => 'required|numeric|min:0.01',
            'paid_at'   => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($order, $data) {

            // Recalculate inside transaction for safety
            $alreadyPaid = $order->payments()->sum('amount');
            $remaining   = max(0, $order->grand_total - $alreadyPaid);

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

            // Create payment
            $order->payments()->create([
                'method'      => $data['method'],
                'amount'      => $data['amount'],
                'paid_at'     => $data['paid_at'],
                'reference'   => $data['reference'] ?? null,
                'status'      => 'completed',
                'received_by'=> auth()->id(),
            ]);

            // Recompute after insert
            $totalPaid = $order->payments()->sum('amount');

            $order->update([
                'payment_status' => $totalPaid >= $order->grand_total ? 'paid' : 'partial',
                'paid_at'        => $totalPaid >= $order->grand_total ? now() : null,
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }
}
