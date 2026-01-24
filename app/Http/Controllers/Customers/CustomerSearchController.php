<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers.search')->only(['search']);
    }

    public function search(Request $request)
    {
        $mobile = trim($request->input('mobile'));

        if (!$mobile) {
            return redirect()
                ->back()
                ->with('error', 'Please enter a mobile number to search.');
        }

        $customer = Customer::where('mobile', 'like', '%' . $mobile . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$customer) {
            return redirect()
                ->route('customers.create', ['mobile' => $mobile])
                ->with('error', 'No customer found. Please create a new customer.');
        }

        return redirect()->route('customers.show', $customer);
    }
}
