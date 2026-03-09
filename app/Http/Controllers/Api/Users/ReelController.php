<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use Illuminate\Http\Request;

class ReelController extends Controller
{

    /**
     * Reel List API
     * GET /api/reels
     */
    public function index(Request $request)
    {

        $reels = Reel::with([
            'platformProduct.product'
        ])
        ->where('status', 1)
        ->latest()
        ->paginate(10);

        $data = $reels->map(function ($reel) {

            return [

                'id' => $reel->id,

                'title' => $reel->title,

                'description' => $reel->description,

                'video' => asset($reel->video),

                'views' => $reel->views,

                'product' => [
                    'id' => $reel->platformProduct->product->id ?? null,
                    'name' => $reel->platformProduct->product->name ?? null,
                    'slug' => $reel->platformProduct->product->slug ?? null,
                    'price' => $reel->platformProduct->price ?? null,
                    'image' => isset($reel->platformProduct->product->image)
                        ? asset($reel->platformProduct->product->image)
                        : null,
                ],

                'created_at' => $reel->created_at,

            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $reels->currentPage(),
                'last_page' => $reels->lastPage(),
                'per_page' => $reels->perPage(),
                'total' => $reels->total()
            ]
        ]);

    }



    /**
     * Single Reel API
     * GET /api/reels/{id}
     */
    public function show($id)
    {

        $reel = Reel::with([
            'platformProduct.product'
        ])
        ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [

                'id' => $reel->id,

                'title' => $reel->title,

                'description' => $reel->description,

                'video' => asset($reel->video),

                'views' => $reel->views,

                'product' => [
                    'id' => $reel->platformProduct->product->id ?? null,
                    'name' => $reel->platformProduct->product->name ?? null,
                    'slug' => $reel->platformProduct->product->slug ?? null,
                    'price' => $reel->platformProduct->price ?? null,
                    'image' => isset($reel->platformProduct->product->image)
                        ? asset($reel->platformProduct->product->image)
                        : null,
                ],

                'created_at' => $reel->created_at,

            ]
        ]);

    }


    /**
     * Increase Reel Views
     */
    public function increaseViews($id)
    {

        $reel = Reel::findOrFail($id);

        $reel->increment('views');

        return response()->json([
            'status' => true,
            'views' => $reel->views
        ]);

    }

}