<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Rate a comic
     */
    public function rate(Request $request, $comicId)
    {
        $validated = $request->validate([
            "rating" => "required|integer|min:1|max:5",
        ]);

        $comic = Comic::findOrFail($comicId);

        // Create or update rating
        Rating::updateOrCreate(
            [
                "user_id" => Auth::id(),
                "comic_id" => $comicId,
            ],
            [
                "rating_value" => $validated["rating"],
            ],
        );

        // Recalculate and format the new average rating
        $newAverage = number_format($comic->averageRating(), 1);

        return response()->json([
            "success" => true,
            "message" => "Rating submitted successfully!",
            "new_average_rating" => $newAverage,
            "total_ratings" => $comic->totalRatings(),
        ]);
    }
}
