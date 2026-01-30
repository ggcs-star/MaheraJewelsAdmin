<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Platform;
use App\Models\ProductVariant;
use App\Models\PlatformPricing;

use App\Models\PlatformProduct;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::select([
            'id',
            'name',
            'sku',
            'slug',
            'category_id',
            'supplier_id',
            'cost_price',
            'base_selling_price',
            'image_url',
            'status',
            'visibility'
        ])
            ->with([
                'category:id,name',
                'supplier:id,name'
            ])

            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })

            ->when(
                $request->filled('category_id'),
                fn($q) =>
                $q->where('category_id', $request->category_id)
            )

            ->when(
                $request->filled('supplier_id'),
                fn($q) =>
                $q->where('supplier_id', $request->supplier_id)
            )

            ->when(
                $request->filled('visibility'),
                fn($q) =>
                $q->where('visibility', $request->visibility)
            )

            ->when(
                $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )
            ->when(
                $request->filled(['adv_field', 'adv_condition', 'adv_value']),
                function ($q) use ($request) {

                    $field = $request->adv_field;
                    $condition = $request->adv_condition;
                    $value = $request->adv_value;

                    $allowedFields = [
                        'name',
                        'sku',
                        'cost_price',
                        'status',
                        'visibility'
                    ];

                    if (!in_array($field, $allowedFields)) {
                        return;
                    }

                    if ($condition === 'like') {

                        $q->where($field, 'LIKE', "%{$value}%");

                    } elseif ($condition === 'starts_with') {

                        $q->where($field, 'LIKE', "{$value}%");

                    } elseif ($condition === 'ends_with') {

                        $q->where($field, 'LIKE', "%{$value}");

                    } else {
                        // =, !=, >, <
                        $q->where($field, $condition, $value);
                    }
                }
            )

            ->orderBy('id', 'desc')
            ->paginate(10);
        $categories = Category::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        $categories = Category::select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::active()
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories', 'suppliers'));
    }
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $productData = $this->validateProduct($request);
            $productData = $this->handleProductImages($request, $productData);

            $product = $this->createProduct($request, $productData);

            $totals = $this->handleVariants($request, $product);

            $this->updateProductPrices($product, $totals);
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product & variants created successfully.');
    }

    private function validateProduct(Request $request): array
    {
        $data = $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',

            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',

            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_top_selling' => 'nullable|boolean',

            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_top_selling'] = $request->boolean('is_top_selling');

        return $data;
    }

    private function handleProductImages(Request $request, array $data): array
    {
        if ($request->hasFile('image_url')) {
            $data['image_url'] =
                $request->file('image_url')->store('products', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];

            foreach ($request->file('gallery_images') as $img) {
                $gallery[] = $img->store('products/gallery', 'public');
            }

            $data['gallery_images'] = $gallery;
        }

        return $data;
    }
    private function createProduct(Request $request, array $data): Product
    {
        $data['cost_price'] = 0;
        $data['base_selling_price'] = 0;

        return Product::create($data);
    }
    private function handleVariants(Request $request, Product $product): array
    {
        $totalPurchase = 0;
        $totalSelling = 0;

        if (!$request->has('variants') || !is_array($request->variants)) {
            return compact('totalPurchase', 'totalSelling');
        }

        foreach ($request->variants as $variant) {

            if (empty($variant['variant_value'])) {
                continue;
            }

            $purchase = (float) ($variant['purchase_price'] ?? 0);
            $selling = (float) ($variant['selling_price'] ?? 0);

            $data = [
                'variant_type' => $variant['variant_type'],
                'variant_value' => $variant['variant_value'],
                'quantity' => $variant['quantity'] ?? 0,
                'purchase_price' => $purchase,
                'total_price' => ($variant['quantity'] ?? 0) * ($variant['purchase_price'] ?? 0),
                'selling_price' => $selling,
                'sku_suffix' => $variant['sku_suffix'] ?? null,
                'sort_order' => $variant['sort_order'] ?? 0,
                'status' => $variant['status'] ?? 'active',
            ];

            if (
                isset($variant['image_url']) &&
                $variant['image_url'] instanceof \Illuminate\Http\UploadedFile
            ) {
                $data['image_url'] =
                    $variant['image_url']->store('products/variants', 'public');
            }

            $product->variants()->create($data);

            $totalPurchase += $purchase;
            $totalSelling += $selling;
        }

        return compact('totalPurchase', 'totalSelling');
    }
    private function updateProductPrices(Product $product, array $totals): void
    {
        $product->update([
            'cost_price' => $totals['totalPurchase'],
            'base_selling_price' => $totals['totalSelling'],
        ]);
    }
    public function edit(Product $product)
    {
        $product->load('variants');

        $categories = Category::select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();
        // dd($categories);
        $suppliers = Supplier::active()
            ->orderBy('name')
            ->get();

        return view('products.edit', compact(
            'product',
            'categories',
            'suppliers'
        ));
    }

    public function update(Request $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {

            $productData = $this->validateProductForUpdate($request, $product);
            $productData = $this->handleProductImagesForUpdate($request, $product, $productData);

            $product->update($productData);

            $product->variants()->delete();

            $totals = $this->handleVariants($request, $product);

            $this->updateProductPrices($product, $totals);
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product & variants updated successfully.');
    }
    private function validateProductForUpdate(Request $request, Product $product): array
    {
        $data = $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,

            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',

            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_top_selling' => 'nullable|boolean',

            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_top_selling'] = $request->boolean('is_top_selling');

        return $data;
    }

    private function handleProductImagesForUpdate(
        Request $request,
        Product $product,
        array $data
    ): array {

        if ($request->hasFile('image_url')) {
            $data['image_url'] =
                $request->file('image_url')->store('products', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];

            foreach ($request->file('gallery_images') as $img) {
                $gallery[] = $img->store('products/gallery', 'public');
            }

            $data['gallery_images'] = $gallery;
        }

        return $data;
    }


    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {

            foreach ($product->variants as $variant) {

                if ($variant->image_url && Storage::disk('public')->exists($variant->image_url)) {
                    Storage::disk('public')->delete($variant->image_url);
                }
            }


            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            if (is_array($product->gallery_images)) {
                foreach ($product->gallery_images as $img) {
                    if (Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }

            $product->variants()->delete();

            $product->delete();
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product deleted successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'category:id,name,slug,parent_id',
            'category.parent:id,name',
            'supplier:id,name,company_name,phone,email,type,commission_type,commission_value',
            'variants' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }
        ]);

        return view('products.show', compact('product'));
    }


public function list()
{
    $pushedProducts = PlatformProduct::with([
        'platform:id,display_name',
        'product.category:id,name',
        'product.supplier:id,name',
        'pricing.variant:id,variant_type,variant_value',
    ])
    ->withSum('pricing as total_stock', 'quantity') // if you have qty column
    ->paginate(10);

    return view('products.list', compact('pushedProducts'));
}




    public function push()
    {
        $products = Product::with([
            'category:id,name,parent_id',
            'category.parent:id,name',
            'variants:id,product_id,variant_type,variant_value,sku_suffix,image_url,sort_order,status,quantity'
        ])
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {

                $category = $product->category;

                $product->display_category = null;
                $product->display_subcategory = null;

                if ($category) {
                    if ($category->parent) {
                        $product->display_category = $category->parent->name;
                        $product->display_subcategory = $category->name;
                    } else {
                        $product->display_category = $category->name;
                        $product->display_subcategory = null;
                    }
                }

                return $product;
            });
        $platforms = Platform::select('id', 'name')->where('is_enabled', true)->get();

        return view('products.push', [
            'products' => $products,
            'platforms' => $platforms,
        ]);
    }

public function pushStore(Request $request)
{
    try {

    
$request->validate([
    'variant_platform_data' => 'required'
]);


    
        //   dd($request->all());

        $data = json_decode($request->variant_platform_data, true);

        if (!$data || !is_array($data)) {
            return back()->with('error', 'No platform data found');
        }

DB::transaction(function () use ($data) {

    foreach ($data as $variantId => $platforms) {

        $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->firstOrFail();

        $totalRequested = collect($platforms)->sum('qty');

        if ($variant->quantity < $totalRequested) {
            throw new \Exception("Total quantity exceeds stock for variant {$variant->variant_value}");
        }

       foreach ($platforms as $platformId => $p) {

    $platformProduct = PlatformProduct::firstOrCreate([
        'platform_id' => $platformId,
        'product_id'  => $variant->product_id,
    ]);

    $discountType = $p['discount_type'] === 'percent' ? 'percentage' : 'fixed';

    PlatformPricing::updateOrCreate(
    [
        'platform_product_id' => $platformProduct->id,
        'product_variant_id'  => $variantId,
    ],
    [
        'price'          => $p['price'],
        'discount_type'  => $discountType,
        'discount_value' => $p['discount_value'],
        'final_price'    => $p['final_total'] / max($p['qty'],1),
        'quantity'       => $p['qty'],
        'currency'       => 'INR',
        'status'         => 'active',
    ]);


        }

        // ⭐ Deduct stock ONCE
        $variant->decrement('quantity', $totalRequested);
    }
});

return redirect()->route('admin.products.list')
    ->with('success', 'Product pushed to marketplace successfully!');


    } catch (\Throwable $e) {
        dd($e->getMessage(), $e->getLine()); // show real error if any
    }
}


    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return redirect()->back();
        }

        Product::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected products deleted successfully');
    }

}
