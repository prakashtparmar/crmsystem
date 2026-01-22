<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = SubCategory::with('category')
            ->latest()
            ->get();

        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:subcategories,slug',
            'is_active'   => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        SubCategory::create($data);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcategory = SubCategory::with('category')->findOrFail($id);

        return view('subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories  = Category::where('is_active', true)->get();

        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:subcategories,slug,' . $subcategory->id,
            'is_active'   => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $subcategory->update($data);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub Category deleted successfully.');
    }

    public function bulkAction(Request $request)
{
    $request->validate([
        'action' => 'required',
        'ids'    => 'required|array',
    ]);

    if ($request->action === 'delete') {
        SubCategory::whereIn('id', $request->ids)->delete();
    }

    return redirect()
        ->route('subcategories.index')
        ->with('success', 'Selected sub categories deleted successfully.');
}

}
