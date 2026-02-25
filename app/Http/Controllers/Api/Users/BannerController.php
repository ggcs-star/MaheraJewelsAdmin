<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Banner::where('status', 1)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                });

            if ($request->has('page') && in_array($request->page, ['home', 'category', 'product'])) {
                $query->where('page', $request->page);
            }

            if ($request->has('position') && in_array($request->position, ['hero', 'mid', 'bottom'])) {
                $query->where('position', $request->position);
            }

            $banners = $query->orderBy('sort_order')->get();

            return response()->json([
                'success' => true,
                'data' => $banners,
                'message' => 'Banners retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $banner = Banner::where('id', $id)
                ->where('status', 1)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->first();

            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Banner retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByPage($page)
    {
        try {
            if (!in_array($page, ['home', 'category', 'product'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid page type'
                ], 400);
            }

            $banners = Banner::where('status', 1)
                ->where('page', $page)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $banners,
                'message' => "Banners for {$page} page retrieved successfully"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByPosition($position)
    {
        try {
            if (!in_array($position, ['hero', 'mid', 'bottom'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid position type'
                ], 400);
            }

            $banners = Banner::where('status', 1)
                ->where('position', $position)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $banners,
                'message' => "Banners for {$position} position retrieved successfully"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}