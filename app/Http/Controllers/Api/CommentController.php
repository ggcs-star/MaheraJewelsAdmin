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
            'username' => 'required|string|max:50',
            'comment' => 'required|string'
        ]);

        $comment = Comment::create([
            'link_id' => $request->link_id,
            'username' => $request->username,
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added',
            'data' => $comment
        ]);
    }

    /**
     * 📥 Get Comments
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
     * 🔢 Count
     */
    public function count($link_id)
    {
        $count = Comment::where('link_id', $link_id)->count();

        return response()->json([
            'status' => true,
            'count' => $count
        ]);
    }

    /**
     * ❌ Delete (simple)
     */
    public function destroy($id)
    {
        Comment::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted'
        ]);
    }
}