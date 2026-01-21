<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Crop;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::query()
        ->with(['category', 'brand', 'images']) // <-- add images here
        ->leftJoinSub(
            DB::table('product_stocks')
                ->selectRaw('product_id, SUM(quantity - reserved_qty) as available_qty')
                ->groupBy('product_id'),
            'stock_totals',
            'products.id',
            '=',
            'stock_totals.product_id'
        )
        ->select(
            'products.*',
            DB::raw('CAST(COALESCE(stock_totals.available_qty, 0) AS UNSIGNED) as available_qty')
        )
        ->latest('products.id')
        ->get();

    return view('products.index', compact('products'));
}


    public function create()
    {
        $categories    = Category::where('is_active', true)->get();
        $subcategories = SubCategory::where('is_active', true)->get();
        $brands        = Brand::where('is_active', true)->get();
        $units         = Unit::all();
        $crops         = Crop::all();
        $seasons       = Season::all();

        return view('products.create', compact(
            'categories',
            'subcategories',
            'brands',
            'units',
            'crops',
            'seasons'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'brand_id'           => 'nullable|exists:brands,id',
            'unit_id'            => 'required|exists:units,id',
            'crop_id'            => 'nullable|exists:crops,id',
            'season_id'          => 'nullable|exists:seasons,id',
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:products,slug',
            'sku'                => 'nullable|string|max:255|unique:products,sku',
            'price'              => 'required|numeric|min:0',
            'cost_price'         => 'nullable|numeric|min:0',
            'short_description'  => 'nullable|string',
            'description'        => 'nullable|string',
            'hsn_code'           => 'nullable|string|max:50',
            'gst_percent'        => 'required|numeric|min:0',
            'is_organic'         => 'boolean',
            'min_order_qty'      => 'required|numeric|min:1',
            'max_order_qty'      => 'nullable|numeric|min:1',
            'shelf_life_days'    => 'nullable|integer|min:1',
            'is_active'          => 'boolean',
            'tags'               => 'nullable|string',
            'images.*'           => 'nullable|image|max:2048',
        ]);

        $data['slug']       = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active']  = $request->boolean('is_active');
        $data['is_organic'] = $request->boolean('is_organic');

        $product = Product::create($data);

        // Tags
        if ($request->filled('tags')) {
            $tags = collect(explode(',', $request->tags))
                ->map(fn ($t) => trim($t))
                ->filter();

            foreach ($tags as $tag) {
                $product->tags()->create(['tag' => $tag]);
            }
        }

        // Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'subcategory',
            'brand',
            'unit',
            'crop',
            'season',
            'variants',
            'attributes',
            'images',
            'tags',
            'certifications',
        ]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories    = Category::where('is_active', true)->get();
        $subcategories = SubCategory::where('is_active', true)->get();
        $brands        = Brand::where('is_active', true)->get();
        $units         = Unit::all();
        $crops         = Crop::all();
        $seasons       = Season::all();

        return view('products.edit', compact(
            'product',
            'categories',
            'subcategories',
            'brands',
            'units',
            'crops',
            'seasons'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'brand_id'           => 'nullable|exists:brands,id',
            'unit_id'            => 'required|exists:units,id',
            'crop_id'            => 'nullable|exists:crops,id',
            'season_id'          => 'nullable|exists:seasons,id',
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'sku'                => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'price'              => 'required|numeric|min:0',
            'cost_price'         => 'nullable|numeric|min:0',
            'short_description'  => 'nullable|string',
            'description'        => 'nullable|string',
            'hsn_code'           => 'nullable|string|max:50',
            'gst_percent'        => 'required|numeric|min:0',
            'is_organic'         => 'boolean',
            'min_order_qty'      => 'required|numeric|min:1',
            'max_order_qty'      => 'nullable|numeric|min:1',
            'shelf_life_days'    => 'nullable|integer|min:1',
            'is_active'          => 'boolean',
            'tags'               => 'nullable|string',
            'images.*'           => 'nullable|image|max:2048',
        ]);

        $data['slug']       = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active']  = $request->boolean('is_active');
        $data['is_organic'] = $request->boolean('is_organic');

        $product->update($data);

        // Sync Tags
        if ($request->has('tags')) {
            $product->tags()->delete();

            $tags = collect(explode(',', $request->tags))
                ->map(fn ($t) => trim($t))
                ->filter();

            foreach ($tags as $tag) {
                $product->tags()->create(['tag' => $tag]);
            }
        }

        // Add New Images
        if ($request->hasFile('images')) {
            $start = $product->images()->count();

            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => false,
                    'sort_order' => $start + $i,
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
