<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\UserReadingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterReaderController extends Controller
{
    /**
     * Display chapter reader page
     */
    public function show($comicId, $chapterId)
    {
        // Load chapter with pages, comic, and related data
        $chapter = Chapter::with([
            "pages" => function ($query) {
                $query->orderBy("page_number", "asc");
            },
            "comic.genres",
        ])->findOrFail($chapterId);

        // Verify chapter belongs to comic
        if ($chapter->comic_id != $comicId) {
            abort(404);
        }

        $comic = $chapter->comic;

        // Get all chapters for navigation
        $allChapters = Chapter::where("comic_id", $comicId)
            ->orderBy("chapter_number", "asc")
            ->get();

        // Find previous and next chapters
        $currentIndex = $allChapters->search(function ($ch) use ($chapterId) {
            return $ch->chapter_id == $chapterId;
        });

        $prevChapter =
            $currentIndex > 0 ? $allChapters[$currentIndex - 1] : null;
        $nextChapter =
            $currentIndex < $allChapters->count() - 1
                ? $allChapters[$currentIndex + 1]
                : null;

        // Update reading progress if user is logged in
        if (Auth::check()) {
            UserReadingProgress::updateOrCreate(
                [
                    "user_id" => Auth::id(),
                    "chapter_id" => $chapterId,
                ],
                [
                    "last_read_at" => now(),
                ],
            );
        }

        // Get total pages count
        $totalPages = $chapter->pages->count();

        return view(
            "chapter-reader",
            compact(
                "chapter",
                "comic",
                "allChapters",
                "prevChapter",
                "nextChapter",
                "totalPages",
            ),
        );
    }
}
