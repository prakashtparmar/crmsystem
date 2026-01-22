<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = ProductAttribute::with('product')->latest()->get();

        return view('product_attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('product_attributes.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name'       => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:255'],
        ]);

        ProductAttribute::create($data);

        return redirect()
            ->route('product-attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductAttribute $product_attribute)
    {
        $products = Product::orderBy('name')->get();

        return view('product_attributes.edit', [
            'attribute' => $product_attribute,
            'products'  => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductAttribute $product_attribute)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name'       => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:255'],
        ]);

        $product_attribute->update($data);

        return redirect()
            ->route('product-attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $product_attribute)
    {
        $product_attribute->delete();

        return redirect()
            ->back()
            ->with('success', 'Attribute deleted successfully.');
    }
}
