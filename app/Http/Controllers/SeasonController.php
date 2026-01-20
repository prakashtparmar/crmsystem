<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::latest()->get();

        return view('seasons.index', compact('seasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seasons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Season::create($data);

        return redirect()
            ->route('seasons.index')
            ->with('success', 'Season created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Season $season)
    {
        return view('seasons.show', compact('season'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Season $season)
    {
        return view('seasons.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $season->update($data);

        return redirect()
            ->route('seasons.index')
            ->with('success', 'Season updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        $season->delete();

        return redirect()
            ->route('seasons.index')
            ->with('success', 'Season deleted successfully.');
    }
}
