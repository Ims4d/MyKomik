<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterManagementController extends Controller
{
    /**
     * Display chapters for a specific comic
     */
    public function index($comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $chapters = Chapter::where('comic_id', $comicId)
            ->orderBy('chapter_number', 'asc')
            ->paginate(15);

        return view('dashboard.chapters.index', compact('comic', 'chapters'));
    }

    /**
     * Show the form for creating a new chapter
     */
    public function create($comicId)
    {
        $comic = Comic::findOrFail($comicId);
        
        // Get next chapter number
        $lastChapter = Chapter::where('comic_id', $comicId)
            ->orderBy('chapter_number', 'desc')
            ->first();
        
        $nextChapterNumber = $lastChapter ? $lastChapter->chapter_number + 1 : 1;

        return view('dashboard.chapters.create', compact('comic', 'nextChapterNumber'));
    }

    /**
     * Store a newly created chapter
     */
    public function store(Request $request, $comicId)
    {
        $validated = $request->validate([
            'chapter_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'pages' => 'required|array|min:1',
            'pages.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
        ]);

        // Create chapter
        $chapter = Chapter::create([
            'comic_id' => $comicId,
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? null,
            'release_date' => $validated['release_date'] ?? now(),
        ]);

        // Upload pages
        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $index => $file) {
                $pageNumber = $index + 1;
                $filename = "page_{$pageNumber}_" . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("comics/pages/{$comicId}/{$chapter->chapter_id}", $filename, 'public');

                Page::create([
                    'comic_id' => $comicId,
                    'chapter_id' => $chapter->chapter_id,
                    'page_number' => $pageNumber,
                    'image_url' => '/storage/' . $path,
                ]);
            }
        }

        return redirect()->route('dashboard.chapters.index', $comicId)
            ->with('success', 'Chapter created successfully with ' . count($request->file('pages')) . ' pages!');
    }

    /**
     * Show the form for editing a chapter
     */
    public function edit($comicId, $chapterId)
    {
        $comic = Comic::findOrFail($comicId);
        $chapter = Chapter::with('pages')->findOrFail($chapterId);

        return view('dashboard.chapters.edit', compact('comic', 'chapter'));
    }

    /**
     * Update the specified chapter
     */
    public function update(Request $request, $comicId, $chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);

        $validated = $request->validate([
            'chapter_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'new_pages' => 'nullable|array',
            'new_pages.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $chapter->update([
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? null,
            'release_date' => $validated['release_date'] ?? $chapter->release_date,
        ]);

        // Add new pages if uploaded
        if ($request->hasFile('new_pages')) {
            $lastPage = Page::where('chapter_id', $chapterId)
                ->orderBy('page_number', 'desc')
                ->first();
            
            $startPageNumber = $lastPage ? $lastPage->page_number + 1 : 1;

            foreach ($request->file('new_pages') as $index => $file) {
                $pageNumber = $startPageNumber + $index;
                $filename = "page_{$pageNumber}_" . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("comics/pages/{$comicId}/{$chapterId}", $filename, 'public');

                Page::create([
                    'comic_id' => $comicId,
                    'chapter_id' => $chapterId,
                    'page_number' => $pageNumber,
                    'image_url' => '/storage/' . $path,
                ]);
            }
        }

        return redirect()->route('dashboard.chapters.index', $comicId)
            ->with('success', 'Chapter updated successfully!');
    }

    /**
     * Remove the specified chapter
     */
    public function destroy($comicId, $chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);

        // Delete all page images
        $pages = Page::where('chapter_id', $chapterId)->get();
        foreach ($pages as $page) {
            if ($page->image_url) {
                $path = str_replace('/storage/', '', $page->image_url);
                Storage::disk('public')->delete($path);
            }
        }

        $chapter->delete();

        return redirect()->route('dashboard.chapters.index', $comicId)
            ->with('success', 'Chapter deleted successfully!');
    }

    /**
     * Delete a single page
     */
    public function deletePage($pageId)
    {
        $page = Page::findOrFail($pageId);
        
        // Delete image file
        if ($page->image_url) {
            $path = str_replace('/storage/', '', $page->image_url);
            Storage::disk('public')->delete($path);
        }

        $page->delete();

        return back()->with('success', 'Page deleted successfully!');
    }
}
