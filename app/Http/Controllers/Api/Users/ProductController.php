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
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
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

                ->select('id','name','slug','brand','image_url','product_price','gallery_images','sort_order')

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

                ->select('id','name','slug','brand','image_url','gallery_images','product_price')
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
    public function searchRedirect(Request $request): JsonResponse
{
    try {
        $platform = Platform::getOwnWebsite();
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return response()->json([
                'success' => false,
                'message' => 'Empty search'
            ], 400);
        }

        $subcategory = Category::query()
            ->whereNotNull('parent_id')
            ->whereRaw('LOWER(name) = ?', [strtolower($query)])
            ->select('id', 'name', 'slug')
            ->first();

        if ($subcategory) {
            return response()->json([
                'success' => true,
                'type' => 'subcategory',
                'id' => $subcategory->id
            ]);
        }

        $category = Category::query()
            ->whereNull('parent_id')
            ->whereRaw('LOWER(name) = ?', [strtolower($query)])
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                }
            ])
            ->first();

        if ($category && $category->children->count() > 0) {
            $firstSubcategory = $category->children->first();
            return response()->json([
                'success' => true,
                'type' => 'subcategory',
                'id' => $firstSubcategory->id  // ✅ FIXED
            ]);
        }

        $product = Product::query()
            ->whereHas('platformListings', function ($q) use ($platform) {
                $q->where('platform_id', $platform->id)->userVisible();
            })
            ->whereRaw('LOWER(name) = ?', [strtolower($query)])
            ->select('slug', 'name')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'type' => 'product',
                'slug' => $product->slug
            ]);
        }

        $subcategory = Category::query()
            ->whereNotNull('parent_id')
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'slug')
            ->first();

        if ($subcategory) {
            return response()->json([
                'success' => true,
                'type' => 'subcategory',
                'id' => $subcategory->id
            ]);
        }

        $category = Category::query()
            ->whereNull('parent_id')
            ->where('name', 'LIKE', "%{$query}%")
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                }
            ])
            ->first();

        if ($category && $category->children->count() > 0) {
            $firstSubcategory = $category->children->first();
            return response()->json([
                'success' => true,
                'type' => 'subcategory',
                'id' => $firstSubcategory->id
            ]);
        }

        $product = Product::query()
            ->whereHas('platformListings', function ($q) use ($platform) {
                $q->where('platform_id', $platform->id)->userVisible();
            })
            ->where('name', 'LIKE', "%{$query}%")
            ->select('slug', 'name')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'type' => 'product',
                'slug' => $product->slug
            ]);
        }

        return response()->json([
            'success' => true,
            'type' => 'search',
            'query' => $query
        ], 200);

    } catch (\Throwable $e) {
        Log::error('Search redirect error', [
            'query' => $request->q,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}
}