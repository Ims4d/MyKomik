<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of genres
     */
    public function index()
    {
        $genres = Genre::withCount('comics')->paginate(15);
        return view('dashboard.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new genre
     */
    public function create()
    {
        return view('dashboard.genres.create');
    }

    /**
     * Store a newly created genre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:genres,name',
        ]);

        Genre::create($validated);

        return redirect()->route('dashboard.genres.index')
            ->with('success', 'Genre created successfully!');
    }

    /**
     * Show the form for editing the specified genre
     */
    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('dashboard.genres.edit', compact('genre'));
    }

    /**
     * Update the specified genre
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:genres,name,' . $id . ',genre_id',
        ]);

        $genre->update($validated);

        return redirect()->route('dashboard.genres.index')
            ->with('success', 'Genre updated successfully!');
    }

    /**
     * Remove the specified genre
     */
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        
        // Check if genre is used by any comics
        if ($genre->comics()->count() > 0) {
            return back()->with('error', 'Cannot delete genre that is being used by comics!');
        }

        $genre->delete();

        return redirect()->route('dashboard.genres.index')
            ->with('success', 'Genre deleted successfully!');
    }
}
