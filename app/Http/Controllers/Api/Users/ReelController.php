<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\ReelLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReelController extends Controller
{

    /**
     * Reel Feed API
     * GET /api/reels
     */
    public function index(Request $request)
    {

        $reels = Reel::with('platformProduct.product')
            ->where('status',1)
            ->latest()
            ->paginate(10);

        $data = $reels->getCollection()->map(function ($reel){

            $product = optional(optional($reel->platformProduct)->product);

            return [

                'id' => $reel->id,
                'title' => $reel->title,
                'description' => $reel->description,

                'video' => Storage::disk('s3')->url($reel->video),

                'views' => $reel->views_count,
                'likes' => $reel->likes_count,
                'shares' => $reel->shares_count,

                'product' => [

                    'id' => $product->id ?? null,
                    'name' => $product->name ?? null,
                    'slug' => $product->slug ?? null,
                    'price' => optional($reel->platformProduct)->platform_price ?? null,
                    'image' => $product->image ? asset($product->image) : null,

                ],

                'created_at' => $reel->created_at

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
     */
    public function show($id)
    {

        $reel = Reel::with('platformProduct.product')->findOrFail($id);

        $product = optional(optional($reel->platformProduct)->product);

        return response()->json([

            'status' => true,

            'data' => [

                'id' => $reel->id,
                'title' => $reel->title,
                'description' => $reel->description,
                'video' => Storage::disk('s3')->url($reel->video),

                'views' => $reel->views_count,
                'likes' => $reel->likes_count,
                'shares' => $reel->shares_count,

                'product' => [

                    'id' => $product->id ?? null,
                    'name' => $product->name ?? null,
                    'slug' => $product->slug ?? null,
                    'price' => optional($reel->platformProduct)->platform_price ?? null,
                    'image' => $product->image ? asset($product->image) : null,

                ],

                'created_at' => $reel->created_at

            ]

        ]);

    }



    /**
     * Increase Reel Views
     */
    public function increaseViews($id)
    {

        $reel = Reel::findOrFail($id);

        $reel->increment('views_count');

        return response()->json([
            'status' => true,
            'views' => $reel->views_count
        ]);

    }



    /**
     * Like Reel (Duplicate Prevent)
     */
   public function like($id, Request $request)
{

    $reel = Reel::findOrFail($id);

    $ip = $request->ip();

    $existing = ReelLike::where('reel_id',$id)
        ->where('ip_address',$ip)
        ->first();


    /* UNLIKE */

    if($existing){

        $existing->delete();

        $reel->decrement('likes_count');

        return response()->json([
            'status'=>true,
            'liked'=>false,
            'likes'=>$reel->likes_count
        ]);

    }


    /* LIKE */

    ReelLike::create([
        'reel_id'=>$id,
        'ip_address'=>$ip
    ]);

    $reel->increment('likes_count');

    return response()->json([
        'status'=>true,
        'liked'=>true,
        'likes'=>$reel->likes_count
    ]);

}



    /**
     * Share Reel
     */
    public function share($id)
    {

        $reel = Reel::findOrFail($id);

        $reel->increment('shares_count');

        return response()->json([
            'status' => true,
            'shares' => $reel->shares_count
        ]);

    }

}