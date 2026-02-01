<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Display the specified chapter.
     *
     * @param  \App\Models\Comic  $comic
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Comic $comic, Chapter $chapter)
    {
        // Ensure the chapter belongs to the comic
        if ($chapter->comic_id !== $comic->comic_id) {
            abort(404);
        }

        $chapter->load('pages');

        // Fetch previous and next chapters
        $previousChapter = Chapter::where('comic_id', $comic->comic_id)
                                  ->where('chapter_number', '<', $chapter->chapter_number)
                                  ->orderBy('chapter_number', 'desc')
                                  ->first();

        $nextChapter = Chapter::where('comic_id', $comic->comic_id)
                              ->where('chapter_number', '>', $chapter->chapter_number)
                              ->orderBy('chapter_number', 'asc')
                              ->first();

        return view('chapters.show', compact('comic', 'chapter', 'previousChapter', 'nextChapter'));
    }
}
