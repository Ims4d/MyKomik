<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChapterController extends Controller
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
        
        $nextChapterNumber = $lastChapter ? floor($lastChapter->chapter_number) + 1 : 1;

        return view('dashboard.chapters.create', compact('comic', 'nextChapterNumber'));
    }

    /**
     * Store a newly created chapter
     */
    public function store(Request $request, $comicId)
    {
        $validated = $request->validate([
            'chapter_number' => 'required|numeric|min:0',
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'upload_id' => 'required|string',
        ]);

        $comic = Comic::findOrFail($comicId);
        $tempDir = 'temp_uploads/' . $validated['upload_id'];

        if (!Storage::disk('private')->exists($tempDir)) {
            return back()->with('error', 'Upload session expired or invalid. Please upload pages again.');
        }

        // Create chapter
        $chapter = Chapter::create([
            'comic_id' => $comic->comic_id,
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? null,
            'release_date' => $validated['release_date'] ?? now(),
        ]);

        // Process uploaded pages from temp storage
        $files = Storage::disk('private')->files($tempDir);
        
        // Sort files alphanumerically
        natsort($files);
        
        $pageCount = 0;
        foreach ($files as $index => $tempPath) {
            $pageNumber = $index + 1;
            $fileContents = Storage::disk('private')->get($tempPath);
            $originalName = pathinfo($tempPath, PATHINFO_BASENAME);
            $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
            
            $newFilename = "page_{$pageNumber}_" . Str::slug($comic->title) . '_' . $chapter->chapter_id . '.' . $extension;
            $newPath = "comics/{$comic->comic_id}/chapters/{$chapter->chapter_id}/{$newFilename}";

            Storage::disk('public')->put($newPath, $fileContents);

            Page::create([
                'comic_id' => $comic->comic_id,
                'chapter_id' => $chapter->chapter_id,
                'page_number' => $pageNumber,
                'image_url' => Storage::url($newPath),
            ]);
            $pageCount++;
        }

        // Clean up temp directory
        Storage::disk('private')->deleteDirectory($tempDir);

        return redirect()->route('dashboard.chapters.index', $comicId)
            ->with('success', "Chapter created successfully with {$pageCount} pages!");
    }


    /**
     * Handle chunked file uploads for pages
     */
    public function uploadChunk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'upload_id' => 'required|string',
            'page' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max per image
            'filename' => 'required|string',
        ]);

        $uploadId = $validated['upload_id'];
        $file = $validated['page'];
        $filename = $validated['filename'];
        
        $tempDir = 'temp_uploads/' . $uploadId;

        // Store the file in a temporary directory
        $file->storeAs($tempDir, $filename, 'private');

        return response()->json(['success' => true]);
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
            'chapter_number' => 'required|numeric|min:0',
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'upload_id' => 'nullable|string', // upload_id is for new pages
        ]);

        $chapter->update([
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? null,
            'release_date' => $validated['release_date'] ?? $chapter->release_date,
        ]);

        // Append new pages if an upload_id is present
        if (!empty($validated['upload_id'])) {
            $tempDir = 'temp_uploads/' . $validated['upload_id'];
            if (Storage::disk('private')->exists($tempDir)) {
                $files = Storage::disk('private')->files($tempDir);
                natsort($files);

                // Use max() for a more robust way to find the last page number
                $lastPageNumber = $chapter->pages()->max('page_number');
                $startPageNumber = $lastPageNumber ? $lastPageNumber + 1 : 1;
                
                foreach ($files as $index => $tempPath) {
                    $pageNumber = $startPageNumber + $index;
                    $fileContents = Storage::disk('private')->get($tempPath);
                    $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                    
                    $newFilename = "page_{$pageNumber}_" . Str::slug($comicId) . '_' . $chapterId . '.' . $extension;
                    $newPath = "comics/{$comicId}/chapters/{$chapterId}/{$newFilename}";

                    Storage::disk('public')->put($newPath, $fileContents);

                    Page::create([
                        'comic_id' => $comicId,
                        'chapter_id' => $chapterId,
                        'page_number' => $pageNumber,
                        'image_url' => Storage::url($newPath),
                    ]);
                }
                Storage::disk('private')->deleteDirectory($tempDir);
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
        $comic = Comic::findOrFail($comicId);

        // Delete the entire chapter directory from public storage
        $chapterDirectory = "comics/{$comic->comic_id}/chapters/{$chapter->chapter_id}";
        Storage::disk('public')->deleteDirectory($chapterDirectory);
        
        // The pages will be deleted from db via cascading delete constraint
        $chapter->delete();

        return redirect()->route('dashboard.chapters.index', $comicId)
            ->with('success', 'Chapter and all its pages deleted successfully!');
    }

    /**
     * Delete a single page
     */
    public function deletePage($pageId): JsonResponse
    {
        try {
            $page = Page::findOrFail($pageId);
            
            // Delete image file from public storage
            if ($page->image_url) {
                $path = str_replace(Storage::url(''), '', $page->image_url);
                Storage::disk('public')->delete($path);
            }

            $page->delete();

            // Optionally, re-order subsequent pages
            // For now, we'll just delete it. Re-ordering can be a separate feature.

            return response()->json(['success' => true, 'message' => 'Page deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}