<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showTrashed = (bool) $request->get('trash');

        $variants = $showTrashed
            ? ProductVariant::onlyTrashed()->with('product')->latest()->get()
            : ProductVariant::with('product')->latest()->get();

        return view('product_variants.index', compact('variants', 'showTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('product_variants.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'sku'        => ['required', 'string', 'max:255', 'unique:product_variants,sku'],
            'weight'     => ['nullable', 'numeric'],
            'price'      => ['required', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'is_default' => ['nullable', 'boolean'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        // If this is set as default, unset other defaults for same product
        if ($data['is_default']) {
            ProductVariant::where('product_id', $data['product_id'])
                ->update(['is_default' => false]);
        }

        ProductVariant::create($data);

        return redirect()
            ->route('product-variants.index')
            ->with('success', 'Variant created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $product_variant)
    {
        $products = Product::orderBy('name')->get();

        return view('product_variants.edit', [
            'variant'  => $product_variant,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $product_variant)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'sku'        => ['required', 'string', 'max:255', 'unique:product_variants,sku,' . $product_variant->id],
            'weight'     => ['nullable', 'numeric'],
            'price'      => ['required', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'is_default' => ['nullable', 'boolean'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        // If set as default, unset other defaults for same product
        if ($data['is_default']) {
            ProductVariant::where('product_id', $data['product_id'])
                ->where('id', '!=', $product_variant->id)
                ->update(['is_default' => false]);
        }

        $product_variant->update($data);

        return redirect()
            ->route('product-variants.index')
            ->with('success', 'Variant updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(ProductVariant $product_variant)
    {
        $product_variant->delete();

        return redirect()
            ->back()
            ->with('success', 'Variant deleted successfully.');
    }

    /**
     * Bulk actions (activate, deactivate, delete, restore)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => ['required', 'string'],
            'ids'    => ['required', 'array'],
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'activate':
                ProductVariant::whereIn('id', $ids)->update(['is_active' => true]);
                break;

            case 'deactivate':
                ProductVariant::whereIn('id', $ids)->update(['is_active' => false]);
                break;

            case 'delete':
                ProductVariant::whereIn('id', $ids)->delete();
                break;

            case 'restore':
                ProductVariant::onlyTrashed()->whereIn('id', $ids)->restore();
                break;
        }

        return redirect()
            ->back()
            ->with('success', 'Bulk action applied successfully.');
    }

    /**
     * Restore a single soft-deleted variant.
     */
    public function restore($id)
    {
        ProductVariant::onlyTrashed()->findOrFail($id)->restore();

        return redirect()
            ->back()
            ->with('success', 'Variant restored successfully.');
    }
}
