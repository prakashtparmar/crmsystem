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
            $order->codSlip()->create([
                'customer_name' => $order->customer_name,
                'mobile'        => $order->customer_phone,
                'alt_mobile'    => $order->alternate_phone ?? null,
                'address'       => $order->shipping_address,
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
