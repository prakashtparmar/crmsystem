<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressLookupController extends Controller
{
    public function lookup(Request $request)
    {
        $pin = preg_replace('/\D/', '', trim($request->get('pincode', '')));

        $query = DB::table('address_master');

        /*
        |--------------------------------------------------------------------------
        | 1) Pincode Handling
        |--------------------------------------------------------------------------
        | - Partial pincode → LIKE search
        | - Full pincode → Exact match
        */
        if ($pin) {
            if (strlen($pin) < 6) {
                $query->where('pincode', 'like', $pin . '%');
            } elseif (strlen($pin) === 6) {
                $query->where('pincode', $pin);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2) Free search fields
        |--------------------------------------------------------------------------
        */
        if ($request->filled('post_office')) {
            $query->where('post_so_name', 'like', '%' . trim($request->post_office) . '%');
        }

        if ($request->filled('village')) {
            $query->where('village_name', 'like', '%' . trim($request->village) . '%');
        }

        if ($request->filled('taluka')) {
            $query->where('taluka_name', 'like', '%' . trim($request->taluka) . '%');
        }

        if ($request->filled('district')) {
            $query->where('District_name', 'like', '%' . trim($request->district) . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | 3) If no meaningful filters → return empty
        |--------------------------------------------------------------------------
        */
        if (
            !$pin &&
            !$request->filled('post_office') &&
            !$request->filled('village') &&
            !$request->filled('taluka') &&
            !$request->filled('district')
        ) {
            return response()->json([]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4) Final Result Set
        |--------------------------------------------------------------------------
        */
        return response()->json(
            $query->select([
                    'pincode',
                    'post_so_name',
                    'village_name',
                    'taluka_name',
                    'District_name',
                    'state_name',
                ])
                ->distinct()
                ->orderBy('pincode')
                ->orderBy('post_so_name')
                ->limit(25)
                ->get()
        );
    }
}
