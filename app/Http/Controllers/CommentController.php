<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request, $comicId)
    {
        $validated = $request->validate([
            "chapter_id" => "required|exists:chapters,chapter_id",
            "comment_text" => "required|string|max:1000",
            "parent_comment_id" => "nullable|exists:comments,comment_id",
        ]);

        $comment = Comment::create([
            "user_id" => Auth::id(),
            "chapter_id" => $validated["chapter_id"],
            "parent_comment_id" => $validated["parent_comment_id"] ?? null,
            "comment_text" => $validated["comment_text"],
        ]);

        return back()->with("success", "Comment posted successfully!");
    }

    /**
     * Delete a comment
     */
    public function destroy($comicId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Check if user owns the comment or is admin
        if (
            $comment->user_id !== Auth::id() &&
            Auth::user()->role !== "admin"
        ) {
            return back()->with(
                "error",
                "You can only delete your own comments.",
            );
        }

        $comment->delete();

        return back()->with("success", "Comment deleted successfully!");
    }
}
