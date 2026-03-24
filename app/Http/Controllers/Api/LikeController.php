<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * Add Like (ONLY INCREASE)
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
}