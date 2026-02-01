<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
            'body' => 'required|string|max:1000', // Assuming 'body' is the name from the textarea
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'comic_id' => $comic->comic_id,
            'comment_text' => $request->body, // Assuming 'body' maps to 'comment_text'
        ]);

        return redirect()->route('comics.show', $comic->comic_id)->with('success', 'Comment posted successfully!');
    }
}
