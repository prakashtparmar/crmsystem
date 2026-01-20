<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Resource index → Enterprise stock overview
     */
    public function index()
    {
        $stocks = ProductStock::with(['product', 'warehouse'])
            ->orderBy('product_id')
            ->get();

        return view('inventory.index', compact('stocks'));
    }

    /**
     * Resource create → redirect to Stock In screen
     */
    public function create()
    {
        return redirect()->route('inventory.in.create');
    }

    /**
     * Resource store → not used in enterprise mode
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Resource show → not applicable
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Resource edit → not applicable
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Resource update → not applicable
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Resource destroy → not applicable
     */
    public function destroy($id)
    {
        abort(404);
    }

    /**
     * Show Stock In / Adjustment form
     */
    public function createIn()
    {
        $products   = Product::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('inventory.in', compact('products', 'warehouses'));
    }

    /**
     * Handle Stock In / Adjustment
     */
    public function storeIn(Request $request, InventoryService $inventory)
    {
        $data = $request->validate([
            'product_id'   => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'qty'          => ['required', 'numeric'],
            'type'         => ['required', 'in:in,adjust'],
            'remarks'      => ['nullable', 'string'],
        ]);

        $inventory->move(
            $data['product_id'],
            $data['warehouse_id'],
            $data['qty'],
            $data['type'],
            'manual',
            null,
            $data['remarks'] ?? null
        );

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Show Transfer form
     */
    public function createTransfer()
    {
        $products   = Product::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('inventory.transfer', compact('products', 'warehouses'));
    }

    /**
     * Handle Warehouse Transfer
     */
    public function transfer(Request $request, InventoryService $inventory)
    {
        $data = $request->validate([
            'product_id'     => ['required', 'exists:products,id'],
            'from_warehouse' => ['required', 'exists:warehouses,id'],
            'to_warehouse'   => ['required', 'exists:warehouses,id', 'different:from_warehouse'],
            'qty'            => ['required', 'numeric', 'min:0.001'],
        ]);

        // OUT from source warehouse
        $inventory->move(
            $data['product_id'],
            $data['from_warehouse'],
            $data['qty'],
            'transfer_out',
            'transfer',
            null,
            'Warehouse transfer (out)'
        );

        // IN to destination warehouse
        $inventory->move(
            $data['product_id'],
            $data['to_warehouse'],
            $data['qty'],
            'transfer_in',
            'transfer',
            null,
            'Warehouse transfer (in)'
        );

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock transferred successfully.');
    }
}
