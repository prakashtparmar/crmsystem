<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CodSlip;
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

            // Convert to Key : Value format
            $address = implode("\n", array_filter([
                isset($lines[0]) ? 'Address Line 1: ' . trim($lines[0]) : null,
                isset($lines[1]) ? 'Landmark: ' . trim($lines[1]) : null,
                isset($lines[2]) ? 'Post Office: ' . trim($lines[2]) : null,
                isset($lines[3]) ? 'Taluka: ' . trim($lines[3]) : null,
                isset($lines[4]) ? 'District: ' . trim($lines[4]) : null,
                isset($lines[5]) ? 'State & Pincode: ' . trim($lines[5]) : null,
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
