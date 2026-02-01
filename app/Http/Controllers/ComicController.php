<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function show(Comic $comic)
    {
        $comic->load('chapters.pages', 'genres', 'comments.user', 'ratings');
        $averageRating = $comic->ratings->avg('rating_value');
        $userRating = $comic->ratings->where('user_id', auth()->id())->first();

        return view('comics.show', compact('comic', 'averageRating', 'userRating'));
    }
}
