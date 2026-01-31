<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComicController extends Controller
{
    /**
     * Display a listing of comics
     */
    public function index(Request $request)
    {
        $query = Comic::with('genres');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $comics = $query->paginate(10);

        return view('dashboard.comics.index', compact('comics'));
    }

    /**
     * Show the form for creating a new comic
     */
    public function create()
    {
        $genres = Genre::all();
        return view('dashboard.comics.create', compact('genres'));
    }

    /**
     * Store a newly created comic
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'status' => 'required|in:ongoing,completed,hiatus',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,genre_id',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('comics/covers', $filename, 'public');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        $comic = Comic::create($validated);

        // Attach genres
        if ($request->has('genres')) {
            $comic->genres()->attach($request->genres);
        }

        return redirect()->route('dashboard.comics.index')
            ->with('success', 'Comic created successfully!');
    }

    /**
     * Display the specified comic
     */
    public function show(Comic $comic)
    {
        $comic->load('genres', 'chapters');
        return view('dashboard.comics.show', compact('comic'));
    }

    /**
     * Show the form for editing the specified comic
     */
    public function edit($id)
    {
        $comic = Comic::with('genres')->findOrFail($id);
        $genres = Genre::all();
        return view('dashboard.comics.edit', compact('comic', 'genres'));
    }

    /**
     * Update the specified comic
     */
    public function update(Request $request, $id)
    {
        $comic = Comic::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'status' => 'required|in:ongoing,completed,hiatus',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,genre_id',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($comic->cover_image_url) {
                $oldPath = str_replace('/storage/', '', $comic->cover_image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('comics/covers', $filename, 'public');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        $comic->update($validated);

        // Sync genres
        if ($request->has('genres')) {
            $comic->genres()->sync($request->genres);
        } else {
            $comic->genres()->detach();
        }

        return redirect()->route('dashboard.comics.index')
            ->with('success', 'Comic updated successfully!');
    }

    /**
     * Remove the specified comic
     */
    public function destroy($id)
    {
        $comic = Comic::findOrFail($id);

        // Delete cover image if exists
        if ($comic->cover_image_url) {
            $oldPath = str_replace('/storage/', '', $comic->cover_image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $comic->delete();

        return redirect()->route('dashboard.comics.index')
            ->with('success', 'Comic deleted successfully!');
    }
}
