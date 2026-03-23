<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * Toggle Like (Like / Unlike)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string'
        ]);

        // 🔥 testing ke liye fallback user
        $userId = auth()->id() ?? 1;

        $like = Like::where('user_id', $userId)
            ->where('link_id', $request->link_id)
            ->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'status' => true,
                'liked' => false,
                'message' => 'Unliked successfully'
            ]);
        }

        Like::create([
            'user_id' => $userId,
            'link_id' => $request->link_id
        ]);

        return response()->json([
            'status' => true,
            'liked' => true,
            'message' => 'Liked successfully'
        ]);
    }

    /**
     * Get Like Count
     */
    public function count($link_id)
    {
        $count = Like::where('link_id', $link_id)->count();

        return response()->json([
            'status' => true,
            'count' => $count
        ]);
    }

    /**
     * Check if user already liked
     */
    public function isLiked($link_id)
    {
        $userId = auth()->id() ?? 1;

        $exists = Like::where('user_id', $userId)
            ->where('link_id', $link_id)
            ->exists();

        return response()->json([
            'status' => true,
            'liked' => $exists
        ]);
    }
}