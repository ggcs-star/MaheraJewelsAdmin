<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * Add Like
     */
    public function store(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string'
        ]);

        Like::create([
            'link_id' => $request->link_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Liked successfully'
        ]);
    }

    /**
     * ❌ DISLIKE (remove 1 like)
     */
    public function dislike(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string'
        ]);

        // 🔥 ek random like delete karo
        $like = Like::where('link_id', $request->link_id)->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'status' => true,
                'message' => 'Disliked successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No likes to remove'
        ]);
    }

    /**
     * Count
     */
    public function count($link_id)
    {
        $count = Like::where('link_id', $link_id)->count();

        return response()->json([
            'status' => true,
            'count' => $count
        ]);
    }
}