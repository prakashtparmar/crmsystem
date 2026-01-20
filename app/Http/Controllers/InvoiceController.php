<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate Invoice
     */
    public function store(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {

                // Prevent duplicate invoice for same order
                if ($order->invoice()->exists()) {
                    throw ValidationException::withMessages([
                        'invoice' => 'An active invoice already exists for this order.',
                    ]);
                }

                // Lock table to avoid race conditions in invoice number generation
                $last = Invoice::lockForUpdate()->max('id');
                $next = ($last ?? 0) + 1;

                $invoiceNumber = 'INV-' . str_pad($next, 6, '0', STR_PAD_LEFT);

                $order->invoice()->create([
                    'invoice_number' => $invoiceNumber,
                    'invoice_date'   => now(),
                    'amount'         => $order->grand_total,
                    'status'         => 'issued',
                ]);

                // Optional: mark order as confirmed if still draft
                if ($order->status === 'draft') {
                    $order->update([
                        'status'     => 'confirmed',
                        'updated_by' => auth()->id(),
                    ]);
                }
            });

            return back()->with('success', 'Invoice generated successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Download Invoice as PDF
     */
    public function download(Order $order)
    {
        $invoice = $order->invoice;

        if (! $invoice) {
            abort(404, 'Invoice not found.');
        }

        $pdf = Pdf::loadView('invoices.show', compact('order', 'invoice'))
            ->setPaper('A4', 'portrait');

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Print Invoice
     */
    public function print(Order $order)
    {
        $invoice = $order->invoice;

        if (! $invoice) {
            abort(404, 'Invoice not found.');
        }

        return view('invoices.print', compact('order', 'invoice'));
    }
}
