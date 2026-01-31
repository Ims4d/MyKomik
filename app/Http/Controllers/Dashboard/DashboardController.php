<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\User;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard home with statistics
     */
    public function index()
    {
        $stats = [
            'total_comics' => Comic::count(),
            'total_chapters' => Chapter::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_comments' => Comment::count(),
            'total_ratings' => Rating::count(),
        ];

        // Recent comics
        $recentComics = Comic::with('genres')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top rated comics
        $topRatedComics = Comic::withCount('ratings')
            ->with('ratings')
            ->having('ratings_count', '>', 0)
            ->get()
            ->map(function($comic) {
                $comic->average_rating = $comic->averageRating();
                return $comic;
            })
            ->sortByDesc('average_rating')
            ->take(5);

        return view('dashboard.index', compact('stats', 'recentComics', 'recentUsers', 'topRatedComics'));
    }
}
