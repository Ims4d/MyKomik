<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with comic listings
     */
    public function index(Request $request)
    {
        $query = Comic::with("genres", "chapters");

        // Search by title or author
        if ($request->has("search") && $request->search != "") {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("title", "like", "%{$search}%")->orWhere(
                    "author",
                    "like",
                    "%{$search}%",
                );
            });
        }

        // Filter by genre
        if ($request->has("genre") && $request->genre != "") {
            $query->whereHas("genres", function ($q) use ($request) {
                $q->where("genres.genre_id", $request->genre);
            });
        }

        // Filter by status
        if ($request->has("status") && $request->status != "") {
            $query->where("status", $request->status);
        }

        // Sorting
        $sort = $request->get("sort", "latest"); // default: latest

        switch ($sort) {
            case "title_asc":
                $query->orderBy("title", "asc");
                break;
            case "title_desc":
                $query->orderBy("title", "desc");
                break;
            case "oldest":
                $query->orderBy("created_at", "asc");
                break;
            case "latest":
            default:
                $query->orderBy("created_at", "desc");
                break;
        }

        // Pagination
        $comics = $query->paginate(12)->withQueryString();

        // Get all genres for filter dropdown
        $genres = Genre::orderBy("name", "asc")->get();

        // Statistics for sidebar/header
        $stats = [
            "total_comics" => Comic::count(),
            "ongoing_comics" => Comic::where("status", "ongoing")->count(),
            "completed_comics" => Comic::where("status", "completed")->count(),
        ];

        // Featured/Popular comics (top 5 by chapter count)
        $featuredComics = Comic::withCount("chapters")
            ->orderBy("chapters_count", "desc")
            ->limit(5)
            ->get();

        return view(
            "home",
            compact("comics", "genres", "stats", "featuredComics"),
        );
    }

    /**
     * Show single comic detail
     */
    public function show($id)
    {
        $comic = Comic::with([
            "genres",
            "chapters" => function ($query) {
                $query->orderBy("chapter_number", "asc");
            },
        ])->findOrFail($id);

        // Calculate average rating
        $averageRating = $comic->averageRating();
        $totalRatings = $comic->totalRatings();

        // Get user's rating if logged in
        $userRating = null;
        if (auth()->check()) {
            $userRating = $comic
                ->ratings()
                ->where("user_id", auth()->id())
                ->first();
        }

        // Latest chapters (for quick access)
        $latestChapters = $comic
            ->chapters()
            ->orderBy("chapter_number", "desc")
            ->limit(5)
            ->get();

        // Get all comments for all chapters of this comic
        // Grouped by chapter, with user and replies
        $comments = \App\Models\Comment::whereHas("chapter", function (
            $query,
        ) use ($id) {
            $query->where("comic_id", $id);
        })
            ->with(["user", "chapter", "replies.user"])
            ->whereNull("parent_comment_id") // Only parent comments
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return view(
            "comic-detail",
            compact(
                "comic",
                "averageRating",
                "totalRatings",
                "userRating",
                "latestChapters",
                "comments",
            ),
        );
    }

    /**
     * Display the user's reading progress library.
     */
    public function library()
    {
        $user = auth()->user();

        $readingProgress = $user->readingProgress()
            ->with(['chapter.comic'])
            // Order by the last time the user read a chapter
            ->orderBy('last_read_at', 'desc')
            ->get()
            // Now that we have all progress, grouped by comic to only show the latest
            ->unique(function ($progress) {
                return $progress->chapter->comic_id;
            });

        return view('library', compact('readingProgress'));
    }
}
