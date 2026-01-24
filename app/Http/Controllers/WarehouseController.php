<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(20);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
        ]);

        Warehouse::create($data);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($data);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated.');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Enterprise rule: do not hard delete if stock exists
        if ($warehouse->stocks()->exists()) {
            return back()->with('error', 'Warehouse has stock. Deactivate instead.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted.');
    }

    // Optional: quick toggle
    public function toggle(Warehouse $warehouse)
    {
        $warehouse->update([
            'is_active' => ! $warehouse->is_active,
        ]);

        return back()->with('success', 'Warehouse status updated.');
    }
}
