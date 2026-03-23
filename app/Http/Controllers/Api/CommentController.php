<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * ➕ Add Comment
     */
    public function store(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string',
            'comment' => 'required|string'
        ]);

        $userId = auth()->id() ?? 1; // testing fallback

        $comment = Comment::create([
            'user_id' => $userId,
            'link_id' => $request->link_id,
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ]);
    }

    /**
     * 📥 Get Comments (by link_id)
     */
    public function index($link_id)
    {
        $comments = Comment::where('link_id', $link_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $comments
        ]);
    }

    /**
     * ❌ Delete Comment
     */
    public function destroy($id)
    {
        $userId = auth()->id() ?? 1;

        $comment = Comment::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$comment) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found or unauthorized'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }

    /**
     * 🔢 Comment Count
     */
    public function count($link_id)
    {
        $count = Comment::where('link_id', $link_id)->count();

        return response()->json([
            'status' => true,
            'count' => $count
        ]);
    }
}