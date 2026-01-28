<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CodSlipController extends Controller
{
    public function store(Order $order)
    {
        if ($order->codSlip()->exists()) {
            return back()->withErrors(['cod' => 'COD slip already exists.']);
        }

        DB::transaction(function () use ($order) {

            // Break stored shipping_address into lines
            $lines = preg_split('/\r\n|\r|\n/', $order->shipping_address ?? '');

            // Helper to normalize each line (remove existing label if present)
            $clean = function ($value, $key) {
                if (!$value) return null;

                // Remove existing "Key:" if already present
                $value = preg_replace('/^' . preg_quote($key, '/') . '\s*:/i', '', trim($value));

                return $key . ': ' . trim($value);
            };

            // Convert to Key : Value format (aligned with real data)
            $address = implode("\n", array_filter([
                isset($lines[0]) ? $clean($lines[0], 'Address Line 1') : null,
                isset($lines[1]) ? $clean($lines[1], 'Landmark') : null,
                isset($lines[2]) ? $clean($lines[2], 'Post Office') : null,
                isset($lines[3]) ? $clean($lines[3], 'Village') : null,
                isset($lines[4]) ? $clean($lines[4], 'Taluka') : null,
                isset($lines[5]) ? $clean($lines[5], 'District') : null,
                isset($lines[6]) ? $clean($lines[6], 'State & Pincode') : null,
                isset($lines[7]) ? $clean($lines[7], 'Country') : null,
            ]));

            $order->codSlip()->create([
                'customer_name' => $order->customer_name,
                'mobile'        => $order->customer_phone,
                'alt_mobile'    => $order->alternate_phone ?? null,
                'address'       => $address,
                'cod_amount'    => $order->grand_total,
            ]);
        });

        return back()->with('success', 'COD Slip generated.');
    }

    public function download(Order $order)
    {
        $slip = $order->codSlip;
        if (! $slip) abort(404);

        $pdf = Pdf::loadView('cod.show', compact('order', 'slip'))
            ->setPaper('A4');

        return $pdf->download('COD-' . $order->id . '.pdf');
    }

    public function print(Order $order)
    {
        $slip = $order->codSlip;
        if (! $slip) abort(404);

        return view('cod.print', compact('order', 'slip'));
    }
}
