<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CropController extends Controller
{
    public function index()
    {
        $crops = Crop::latest()->get();
        return view('crops.index', compact('crops'));
    }

    public function create()
    {
        return view('crops.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:crops,slug',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        Crop::create($data);

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop created successfully.');
    }

    public function show(Crop $crop)
    {
        return view('crops.show', compact('crop'));
    }

    public function edit(Crop $crop)
    {
        return view('crops.edit', compact('crop'));
    }

    public function update(Request $request, Crop $crop)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:crops,slug,' . $crop->id,
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $crop->update($data);

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop updated successfully.');
    }

    public function destroy(Crop $crop)
    {
        $crop->delete();

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop deleted successfully.');
    }
}
