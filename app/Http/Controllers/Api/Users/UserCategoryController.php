<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCategoryOrder;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class UserCategoryController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $ordered = UserCategoryOrder::where('user_id',$user->id)
            ->orderBy('position')
            ->pluck('category_id')
            ->toArray();

        if(empty($ordered)){
            $categories = Category::whereNull('parent_id')
                ->where('status','active')
                ->where('visibility','public')
                ->orderBy('sort_order')
                ->get();
        }else{

            $categories = Category::whereIn('id',$ordered)
                ->orderByRaw("FIELD(id,".implode(',',$ordered).")")
                ->get();
        }

        return response()->json([
            'success'=>true,
            'data'=>$categories
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'categories'=>'required|array'
        ]);

        foreach($request->categories as $index=>$categoryId){

            UserCategoryOrder::updateOrCreate(
                [
                    'user_id'=>$user->id,
                    'category_id'=>$categoryId
                ],
                [
                    'position'=>$index
                ]
            );
        }

        return response()->json([
            'success'=>true,
            'message'=>'Category order saved'
        ]);
    }

}