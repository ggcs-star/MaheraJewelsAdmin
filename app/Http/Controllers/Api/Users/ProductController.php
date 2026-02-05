<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Platform;
use App\Transformers\ProductListTransformer;
use App\Transformers\ProductDetailTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $platform = Platform::getOwnWebsite();

            $products = Product::query()
                ->whereHas('platformListings', fn ($q) =>
                    $q->where('platform_id', $platform->id)->userVisible()
                )
                ->with([
                    'category:id,name',
                    'platformListings' => fn ($q) =>
                        $q->where('platform_id', $platform->id)->userVisible(),
                    'variants.platformPricings' => fn ($q) =>
                        $q->where('status', 'active'),
                ])
                ->latest()
                ->paginate(12);

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products->getCollection()
                        ->map(fn ($p) => ProductListTransformer::transform($p)),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'last_page' => $products->lastPage(),
                    ],
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Landing Product API Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $platform = Platform::getOwnWebsite();

            $product = Product::query()
                ->where('slug', $slug)
                ->whereHas('platformListings', fn ($q) =>
                    $q->where('platform_id', $platform->id)->userVisible()
                )
          ->with([
    'category:id,name',

    // BASE VARIANT FIELDS
    'variants:id,product_id,variant_id,variant_value_id,quantity,selling_price,image_url,sku_suffix,status',

    // RELATIONS
    'variants.variant:id,name',
    'variants.value:id,value',

    'variants.platformPricings' => fn ($q) =>
        $q->where('status', 'active'),
])

                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => ProductDetailTransformer::transform($product),
            ]);
        } catch (Throwable $e) {
            Log::error('Product Detail API Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
    }
}