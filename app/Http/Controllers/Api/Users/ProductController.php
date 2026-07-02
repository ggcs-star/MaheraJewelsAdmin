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
        $products = Product::query()
            ->where('is_top_selling', 1)
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->select('id', 'name', 'slug', 'brand','product_price' , 'gallery_images')
            ->with([
                'category:id,name',
                'variants:id,product_id,variant_id,variant_value_id,quantity,selling_price,image_url,sku_suffix,status',
                'variants.variant:id,name',
                'variants.value:id,value',
            ])
            ->orderBy('sort_order')
            ->limit(10)
            ->get()
            ->map(fn ($p) => ProductListTransformer::transform($p));

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products
            ],
        ]);
    }

    public function bestSeller(): JsonResponse
{
    try {

        $platform = Platform::getOwnWebsite();

        // Manual Best Seller Count
        $manualCount = Product::query()
            ->where('is_best_seller', 1)
            ->count();

        // ===========================
        // Manual Best Seller
        // ===========================
        if ($manualCount > 0) {

            $products = Product::query()
                ->where('is_best_seller', 1)
                ->where('status', 'active')
                ->where('visibility', 'public')

                ->whereHas('platformListings', fn ($q) =>
                    $q->where('platform_id', $platform->id)
                      ->userVisible()
                )

                ->with([
                    'category:id,name',
                    'platformListings' => fn ($q) =>
                        $q->where('platform_id', $platform->id)
                          ->userVisible(),

                    'variants.platformPricings' => fn ($q) =>
                        $q->where('status', 'active'),
                ])

                ->orderBy('sort_order')
                ->limit(10)
                ->get();

        } else {

            // ===========================
            // Automatic Best Seller
            // ===========================
            $products = Product::query()

                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')

                // Apna delivered status yahan change karna
                ->where('orders.status', 'delivered')

                ->where('products.status', 'active')
                ->where('products.visibility', 'public')

                ->whereHas('platformListings', fn ($q) =>
                    $q->where('platform_id', $platform->id)
                      ->userVisible()
                )

                ->select(
                    'products.*',
                    DB::raw('SUM(order_items.quantity) as total_sold')
                )

                ->groupBy('products.id')
                ->orderByDesc('total_sold')

                ->with([
                    'category:id,name',

                    'platformListings' => fn ($q) =>
                        $q->where('platform_id', $platform->id)
                          ->userVisible(),

                    'variants.platformPricings' => fn ($q) =>
                        $q->where('status', 'active'),
                ])

                ->limit(10)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products->map(fn ($p) => ProductListTransformer::transform($p))
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