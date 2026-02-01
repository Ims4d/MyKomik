<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Comic $comic)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $query = Rating::where('user_id', Auth::id())
                       ->where('comic_id', $comic->comic_id);

        $rating = $query->first();

        if ($rating) {
            // Use update on the query builder for composite primary keys
            $query->update(['rating_value' => $request->rating]);
        } else {
            Rating::create([
                'user_id' => Auth::id(),
                'comic_id' => $comic->comic_id,
                'rating_value' => $request->rating,
            ]);
        }

        return redirect()->route('comics.show', $comic->comic_id)->with('success', 'Rating submitted successfully!');
    }
}
