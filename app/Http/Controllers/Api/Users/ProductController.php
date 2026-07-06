<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Platform;
use App\Models\Category;
use App\Transformers\ProductListTransformer;
use App\Transformers\ProductDetailTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ProductClick;
use Illuminate\Support\Facades\DB;
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

                
                'variants:id,product_id,variant_id,variant_value_id,quantity,selling_price,image_url,sku_suffix,status,color',

                'variants.variant:id,name',
                'variants.value:id,value',

                'variants.platformPricings' => fn ($q) =>
                    $q->where('status', 'active'),
            ])

                ->firstOrFail();
   $click = ProductClick::firstOrCreate(
    ['product_id' => $product->id],
    [
        'click_count' => 0,
        'user_id' => auth()->id(),
        'ip_address' => request()->ip(),
        'device_id' => request()->header('X-Device-ID'),
    ]
);

$click->increment('click_count');
$product->click_count = $click->fresh()->click_count;

            return response()->json([
                'success' => true,
                'data' => ProductDetailTransformer::transform($product),
            ]);
        } catch (Throwable $e) {
            Log::error('Product Detail API Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
    }
  public function showById(int $product_id): JsonResponse
    {
        try {
            $product = Product::with([
                'category:id,name',
                'variants:id,product_id,variant_id,variant_value_id,quantity,selling_price,image_url,sku_suffix,status,color',
                'variants.variant:id,name',
                'variants.value:id,value',
                'variants.platformPricings' => fn ($q) =>
                    $q->where('status', 'active'),
            ])->findOrFail($product_id);
$click = ProductClick::firstOrCreate(
    ['product_id' => $product->id],
    [
        'click_count' => 0,
        'user_id' => auth()->id(),
        'ip_address' => request()->ip(),
        'device_id' => request()->header('X-Device-ID'),
    ]
);

$click->increment('click_count');
$product->click_count = $click->fresh()->click_count;
            return response()->json([
                'success' => true,
                'data' => ProductDetailTransformer::transform($product),
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
    }
public function topSelling(): JsonResponse
{
    try {

        $platform = Platform::getOwnWebsite();

        // Manual Top Selling Count
        $manualCount = Product::where('is_top_selling', 1)->count();

        if ($manualCount > 0) {

            // ===========================
            // Manual Top Selling
            // ===========================
            $products = Product::query()

                ->leftJoin('product_clicks', 'products.id', '=', 'product_clicks.product_id')

                ->where('products.is_top_selling', 1)
                ->where('products.status', 'active')
                ->where('products.visibility', 'public')

                ->whereHas('platformListings', function ($q) use ($platform) {
                    $q->where('platform_id', $platform->id)
                        ->userVisible();
                })

                ->with([
                    'category:id,name',

                    'platformListings' => function ($q) use ($platform) {
                        $q->where('platform_id', $platform->id)
                            ->userVisible();
                    },

                    'variants.platformPricings' => function ($q) {
                        $q->where('status', 'active');
                    },
                ])

                ->select(
                    'products.*',
                    DB::raw('COALESCE(product_clicks.click_count, 0) as click_count')
                )

                ->orderBy('products.sort_order')

                ->limit(10)

                ->get();

        } else {

            // ===========================
            // Automatic Top Selling
            // (Most Clicked Products)
            // ===========================

            $products = Product::query()

                ->leftJoin('product_clicks', 'products.id', '=', 'product_clicks.product_id')

                ->where('products.status', 'active')
                ->where('products.visibility', 'public')

                ->whereHas('platformListings', function ($q) use ($platform) {
                    $q->where('platform_id', $platform->id)
                        ->userVisible();
                })

                ->with([
                    'category:id,name',

                    'platformListings' => function ($q) use ($platform) {
                        $q->where('platform_id', $platform->id)
                            ->userVisible();
                    },

                    'variants.platformPricings' => function ($q) {
                        $q->where('status', 'active');
                    },
                ])

                ->select(
                    'products.*',
                    DB::raw('COALESCE(product_clicks.click_count, 0) as click_count')
                )

                ->orderByDesc('click_count')

                ->limit(10)

                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products->map(fn ($p) => ProductListTransformer::transform($p))
            ],
        ]);

    } catch (\Throwable $e) {

        Log::error('Top Selling API Error', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}

public function bestSeller(): JsonResponse
{
    try {

        $platform = Platform::getOwnWebsite();

        // Manual Best Seller Count
        $manualCount = Product::where('is_best_seller', 1)->count();

        if ($manualCount > 0) {

            // ===========================
            // Manual Best Seller
            // ===========================
            $products = Product::query()

                ->leftJoin('product_clicks', 'products.id', '=', 'product_clicks.product_id')

                ->where('products.is_best_seller', 1)
                ->where('products.status', 'active')
                ->where('products.visibility', 'public')

                ->whereHas('platformListings', function ($q) use ($platform) {
                    $q->where('platform_id', $platform->id)
                        ->userVisible();
                })

                ->with([
                    'category:id,name',

                    'platformListings' => function ($q) use ($platform) {
                        $q->where('platform_id', $platform->id)
                            ->userVisible();
                    },

                    'variants.platformPricings' => function ($q) {
                        $q->where('status', 'active');
                    },
                ])

                ->select(
                    'products.*',
                    DB::raw('COALESCE(product_clicks.click_count,0) as click_count')
                )

                ->orderBy('products.sort_order')
                ->limit(10)
                ->get();

        } else {

            // ===========================
            // Automatic Best Seller
            // ===========================

            $soldProducts = DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', 'delivered')
                ->select(
                    'order_items.product_id',
                    DB::raw('SUM(order_items.quantity) as total_sold')
                )
                ->groupBy('order_items.product_id')
                ->orderByDesc('total_sold')
                ->limit(10)
                ->pluck('product_id');

            $products = Product::query()

                ->leftJoin('product_clicks', 'products.id', '=', 'product_clicks.product_id')

                ->whereIn('products.id', $soldProducts)
                ->where('products.status', 'active')
                ->where('products.visibility', 'public')

                ->whereHas('platformListings', function ($q) use ($platform) {
                    $q->where('platform_id', $platform->id)
                        ->userVisible();
                })

                ->with([
                    'category:id,name',

                    'platformListings' => function ($q) use ($platform) {
                        $q->where('platform_id', $platform->id)
                            ->userVisible();
                    },

                    'variants.platformPricings' => function ($q) {
                        $q->where('status', 'active');
                    },
                ])

                ->select(
                    'products.*',
                    DB::raw('COALESCE(product_clicks.click_count,0) as click_count')
                )

                ->get()

                // Keep same ranking as total sold
                ->sortBy(function ($product) use ($soldProducts) {
                    return array_search($product->id, $soldProducts->toArray());
                })

                ->values();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products->map(fn($p) => ProductListTransformer::transform($p))
            ]
        ]);

    } catch (\Throwable $e) {

        Log::error('Best Seller API Error', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}

    public function searchSuggestions(Request $request): JsonResponse
    {
        try {

            $platform = Platform::getOwnWebsite();
            $query = trim($request->get('q',''));

            if(strlen($query) < 2){
                return response()->json([
                    'success'=>true,
                    'data'=>[
                        'products'=>[],
                        'categories'=>[],
                        'brands'=>[]
                    ]
                ]);
            }

            $products = Product::query()
                ->select('id','name','slug','brand')

                ->whereHas('platformListings', fn($q)=>
                    $q->where('platform_id',$platform->id)->userVisible()
                )

                ->where('name','LIKE',"%{$query}%")
                ->limit(5)
                ->get();

            $categories = Category::query()
                ->select('id','name','slug')
                ->where('name','LIKE',"%{$query}%")
                ->limit(5)
                ->get();

            $brands = Product::query()
                ->whereNotNull('brand')
                ->where('brand','LIKE',"%{$query}%")
                ->distinct()
                ->limit(5)
                ->pluck('brand');

            return response()->json([
                'success'=>true,
                'data'=>[
                    'products'=>$products,
                    'categories'=>$categories,
                    'brands'=>$brands
                ]
            ]);

        } catch(Throwable $e){

            Log::error('Search suggestion error',[
                'error'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Suggestion failed'
            ],500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {

            $platform = Platform::getOwnWebsite();
            $query = trim($request->get('q',''));

            if($query === ''){
                return response()->json([
                    'success'=>true,
                    'data'=>[
                        'products'=>[],
                        'pagination'=>null
                    ]
                ]);
            }

            $products = Product::query()

                ->whereHas('platformListings', fn($q)=>
                    $q->where('platform_id',$platform->id)->userVisible()
                )

                ->where(function($q) use ($query){

                    $q->where('name','LIKE',"%{$query}%")
                    ->orWhere('brand','LIKE',"%{$query}%")
                    ->orWhere('description','LIKE',"%{$query}%")
                    ->orWhere('short_description','LIKE',"%{$query}%")
                    ->orWhere('meta_title','LIKE',"%{$query}%")
                    ->orWhere('meta_description','LIKE',"%{$query}%")

                    ->orWhereHas('category', function($cat) use ($query){
                        $cat->where('name','LIKE',"%{$query}%");
                    });

                })

                ->with([
                    'category:id,name',
                    'platformListings' => fn($q)=>
                        $q->where('platform_id',$platform->id)->userVisible(),
                    'variants.platformPricings'=>fn($q)=>
                        $q->where('status','active')
                ])

                ->select('id','name','slug','brand','image_url','product_price','sort_order')

                ->orderBy('sort_order','asc')
                ->latest()
                ->paginate(12);

            return response()->json([
                'success'=>true,
                'data'=>[
                    'products'=>$products->getCollection()
                        ->map(fn($p)=>ProductListTransformer::transform($p)),
                    'pagination'=>[
                        'current_page'=>$products->currentPage(),
                        'per_page'=>$products->perPage(),
                        'total'=>$products->total(),
                        'last_page'=>$products->lastPage()
                    ]
                ]
            ]);

        } catch(Throwable $e){

            Log::error('Search error',[
                'query'=>$request->q,
                'error'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Search failed'
            ],500);
        }
    }
    
    public function unifiedSearch(Request $request): JsonResponse
    {
        try {

            $platform = Platform::getOwnWebsite();
            $query = trim($request->get('q',''));

            if ($query === '') {
                return response()->json([
                    'success'=>true,
                    'data'=>[
                        'categories'=>[],
                        'subcategories'=>[],
                        'products'=>[]
                    ]
                ]);
            }

            $categories = Category::query()
                ->whereNull('parent_id')
                ->where('name','LIKE',"%{$query}%")
                ->with('children:id,name,slug,image_url,parent_id')
                ->limit(5)
                ->get();

            $subcategories = Category::query()
                ->whereNotNull('parent_id')
                ->where('name','LIKE',"%{$query}%")
                ->limit(5)
                ->get();

            $products = Product::query()

                ->whereHas('platformListings', fn($q)=>
                    $q->where('platform_id',$platform->id)->userVisible()
                )

                ->where(function($q) use ($query){
                    $q->where('name','LIKE',"%{$query}%")
                    ->orWhere('brand','LIKE',"%{$query}%")
                    ->orWhereHas('category', function($cat) use ($query){
                        $cat->where('name','LIKE',"%{$query}%");
                    });
                })

                ->select('id','name','slug','brand','image_url','product_price')
                ->limit(10)
                ->get()
                ->map(fn($p)=>ProductListTransformer::transform($p));

            return response()->json([
                'success'=>true,
                'data'=>[
                    'categories'=>$categories,
                    'subcategories'=>$subcategories,
                    'products'=>$products
                ]
            ]);

        } catch(Throwable $e){

            Log::error('Unified search error',[
                'error'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Search failed'
            ],500);
        }
    }
}