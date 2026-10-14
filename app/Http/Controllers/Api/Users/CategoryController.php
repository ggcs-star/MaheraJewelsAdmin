<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Platform;
use App\Transformers\ProductListTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Get all active, public parent categories and their children.
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::query()
                ->whereNull('parent_id')
                ->where('visibility', 'public')
                ->where('status', 'active')
                ->with([
                    'children' => function ($query) {
                        $query->where('visibility', 'public')
                            ->where('status', 'active')
                            ->orderBy('sort_order');
                    },
                ])
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image_url' => $category->image_url,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                    'children' => $category->children->map(fn ($child) => [
                        'id' => $child->id,
                        'name' => $child->name,
                        'slug' => $child->slug,
                        'image_url' => $child->image_url,
                        'created_at' => $child->created_at,
                        'updated_at' => $child->updated_at,
                    ])->values(),
                ])->values(),
            ]);
        } catch (Throwable $e) {
            Log::error('Category List API Error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch categories',
            ], 500);
        }
    }

    /**
     * Get one active, public category.
     */
    public function show(int $category_id): JsonResponse
    {
        try {
            $category = Category::query()
                ->whereKey($category_id)
                ->where('visibility', 'public')
                ->where('status', 'active')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image_url' => $category->image_url,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Category Detail API Error', [
                'message' => $e->getMessage(),
                'category_id' => $category_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }
    }

    /**
     * Get products assigned to a category through category_product.
     */
    public function products(Category $category): JsonResponse
    {
        try {
            // Do not expose inactive or private categories.
            if (
                $category->visibility !== 'public' ||
                $category->status !== 'active'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $platform = Platform::getOwnWebsite();

            if (! $platform) {
                Log::error('Category Products API: Website platform not found');

                return response()->json([
                    'success' => false,
                    'message' => 'Website platform is not configured',
                ], 500);
            }

            // Aggregate clicks first so multiple click rows cannot duplicate products.
            $clickCounts = DB::table('product_clicks')
                ->select('product_id')
                ->selectRaw('SUM(click_count) as click_count')
                ->groupBy('product_id');

            $products = $category->products()
                ->leftJoinSub(
                    $clickCounts,
                    'product_clicks_summary',
                    function ($join) {
                        $join->on(
                            'products.id',
                            '=',
                            'product_clicks_summary.product_id'
                        );
                    }
                )
                ->whereHas('platformListings', function ($query) use ($platform) {
                    $query->where('platform_id', $platform->id)
                        ->userVisible();
                })
                ->with([
                    // Keep the legacy primary category relationship available.
                    'category:id,name',

                    // Include all categories assigned through the pivot.
                    'categories:id,name,slug',

                    'variants.platformPricings' => function ($query) {
                        $query->where('status', 'active');
                    },
                ])
                ->select(
                    'products.*',
                    DB::raw(
                        'COALESCE(product_clicks_summary.click_count, 0) as click_count'
                    )
                )
                ->orderByDesc('click_count')
                ->paginate(12);

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'image_url' => $category->image_url,
                    ],
                    'products' => $products->getCollection()
                        ->map(fn ($product) =>
                            ProductListTransformer::transform($product)
                        )
                        ->values(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ],
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Category Products API Error', [
                'message' => $e->getMessage(),
                'category_id' => $category->id ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch category products',
            ], 500);
        }
    }
}
