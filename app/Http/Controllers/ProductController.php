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
use App\Models\Warehouse;
use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Support\Str;



class ProductController extends Controller
{

private function isUploadedFile($file): bool
{
    return $file instanceof \Illuminate\Http\UploadedFile;
}
    public function index(Request $request)
    {
        $products = Product::select([
            'id',
            'name',
            'sku',
            'slug',
            'category_id',
            'supplier_id',
            'warehouse_id',
            'cost_price',
            'base_selling_price',
            'image_url',
            'gallery_images',
            'status',
            'visibility'
        ])
            ->with([
                'category:id,name',
                'supplier:id,name',
                'warehouse:id,name,city'
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

    public function create(Request $request)
    {
        
        $categories = Category::select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::active()
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('status', 'active')
        ->orderBy('city')
        ->get();

         $variants = Variant::with('values')
        ->where('is_active', 1)
        ->get();
        return view('products.create', compact(
        'categories',
        'suppliers',
        'warehouses',
        'variants'
    ));
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
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'expected_delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:50',


        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_top_selling'] = $request->boolean('is_top_selling');

        return $data;
    }

        private function handleProductImages(Request $request, array $data): array
        {
        $productSlug = Str::slug($request->name);

            if ($request->hasFile('image_url')) {
                $data['image_url'] = $request->file('image_url')
                    ->store("admin/product/{$productSlug}", 's3');
            }
            if ($request->hasFile('gallery_images')) {
                $gallery = [];

                foreach ($request->file('gallery_images') as $img) {
                    $gallery[] = $img->store(
                        "admin/product/{$productSlug}",
                        's3'
                    );
                }

                $data['gallery_images'] = $gallery;
            }

            return $data;
        }

    private function createProduct(Request $request, array $data): Product
    {
        $data['cost_price'] = 0;
        $data['base_selling_price'] = 0;

          $data['expected_delivery_date'] = $request->expected_delivery_date;
          $data['payment_terms'] = $request->payment_terms;

        return Product::create($data);
    }

private function handleVariants(Request $request, Product $product): array
{
    $totalPurchase = 0;
    $totalSelling  = 0;

    if (!$request->has('variants')) {
        return compact('totalPurchase', 'totalSelling');
    }

    $seen = []; 

    foreach ($request->variants as $variant) {

        if (
            empty($variant['variant_id']) ||
            empty($variant['variant_value_id'])
        ) {
            continue;
        }

        $variantId = (int) $variant['variant_id'];
        $valueId   = (int) $variant['variant_value_id'];

        $key = $variantId . '-' . $valueId;
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;

        $qty      = (int) ($variant['quantity'] ?? 0);
        $purchase = (float) ($variant['purchase_price'] ?? 0);
        $selling = (float) ($variant['selling_price'] ?? 0);


        if ($qty <= 0) continue;

       $imagePath = null;

        if (
            isset($variant['image_url'])
            && $this->isUploadedFile($variant['image_url'])) {
            $productSlug = Str::slug($product->slug ?? $product->name);

            $variantSlug = Str::slug(
                ($variant['sku_suffix'] ?? 'variant') . '-' . ($variant['variant_value_id'] ?? uniqid())
            );

            $imagePath = $variant['image_url']->store(
                "admin/product/{$productSlug}/variant/{$variantSlug}",
                's3'
            );
        }

        ProductVariant::create([
            'product_id'       => $product->id,
            'variant_id'       => $variantId,
            'variant_value_id' => $valueId,
            'quantity'         => $qty,
            'purchase_price'   => $purchase,
            'selling_price'    => $selling,
            'total_price'      => $qty * $purchase,
            'sku_suffix'       => $variant['sku_suffix'] ?? null,
            'sort_order'       => $variant['sort_order'] ?? 0,
            'status'           => $variant['status'] ?? 'active',
            'color'            => $variant['color'] ?? null,
            'height'           => $variant['height'] ?? null,
            'width'            => $variant['width'] ?? null,
            'image_url'        => $imagePath,
        ]);


        $totalPurchase += $qty * $purchase;
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

    $suppliers = Supplier::active()
        ->orderBy('name')
        ->get();

    $warehouses = Warehouse::where('status', 'active')
        ->orderBy('city')
        ->get();
     $variants = Variant::with('values')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();


    return view('products.edit', compact(
        'product',
        'categories',
        'suppliers',
        'warehouses',
        'variants'
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

            'warehouse_id' => 'nullable|exists:warehouses,id',
            'expected_delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:50',

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

            $productSlug = Str::slug($product->slug ?? $product->name);

            if ($request->hasFile('image_url')) {
                $data['image_url'] = $request->file('image_url')
                    ->store("admin/product/{$productSlug}", 's3');
            }

            if ($request->hasFile('gallery_images')) {

                $existingImages = is_array($product->gallery_images)
                    ? $product->gallery_images
                    : [];

                $newImages = [];

                foreach ($request->file('gallery_images') as $img) {
                    $newImages[] = $img->store(
                        "admin/product/{$productSlug}",
                        's3'
                    );
                }

                $data['gallery_images'] = array_merge($existingImages, $newImages);
            }

            return $data;
        }


    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {

            foreach ($product->variants as $variant) {

                if ($variant->image_url && Storage::disk('s3')->exists($variant->image_url)) {
                    Storage::disk('s3')->delete($variant->image_url);
                }
            }


            if ($product->image_url && Storage::disk('s3')->exists($product->image_url)) {
                Storage::disk('s3')->delete($product->image_url);
            }

            if (is_array($product->gallery_images)) {
        foreach ($product->gallery_images as $img) {
            if (Storage::disk('s3')->exists($img)) {
                Storage::disk('s3')->delete($img);
            }
        }
    }

        if ($product->image_url && Storage::disk('s3')->exists($product->image_url)) {
            Storage::disk('s3')->delete($product->image_url);
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
    'warehouse:id,name,city',
'variants' => function ($query) {
    $query->with(['variant:id,name', 'value:id,value'])
          ->orderBy('sort_order')
          ->orderBy('id');
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
            'pricing.variant.variant:id,name',
            'pricing.variant.value:id,value'
        ])->paginate(10);


            return view('products.list', compact('pushedProducts'));
        }


        public function push()
        {
            $products = Product::with([
                'category:id,name,parent_id',
                'category.parent:id,name',

                // ⭐ Load relations instead of columns
                'variants:id,product_id,variant_id,variant_value_id,sku_suffix,image_url,sort_order,status,quantity,purchase_price,selling_price',
                'variants.variant:id,name',
                'variants.value:id,value',

                'variants.platformPricings.platformProduct'
            ])
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {

                $category = $product->category;

                if ($category) {
                    if ($category->parent) {
                        $product->display_category = $category->parent->name;
                        $product->display_subcategory = $category->name;
                    } else {
                        $product->display_category = $category->name;
                        $product->display_subcategory = null;
                    }
                }

                // ⭐ Variant payload using relations
                $product->variant_payload = $product->variants->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'variant_type'  => $v->variant->name,   // from variants table
                        'variant_value' => $v->value->value,    // from variant_values table
                        'sku_suffix' => $v->sku_suffix,
                        'quantity' => $v->quantity,
                        'purchase_price' => $v->purchase_price,
                        'selling_price'  => $v->selling_price,
                    ];
                });

                return $product;
            });

            $existingConfig = [];

            foreach ($products as $product) {
                foreach ($product->variants as $variant) {

                    foreach ($variant->platformPricings as $pricing) {

                        $platformId = $pricing->platformProduct->platform_id;

                        $existingConfig[$variant->id][$platformId] = [
                            'price' => $pricing->price,
                            'qty'   => $pricing->quantity,
                            'discount_value' => $pricing->discount_value,
                            'discount_type'  => $pricing->discount_type === 'percentage' ? 'percent' : 'amount',
                            'final_total'    => $pricing->quantity * $pricing->final_price
                        ];
                    }
                }
            }

            $platforms = Platform::select('id', 'name')
                ->where('is_enabled', true)
                ->get();

            return view('products.push', [
                'products' => $products,
                'platforms' => $platforms,
                'existingVariantPlatformData' => $existingConfig
            ]);
        }


        public function pushStore(Request $request)
        {
            \Log::info('PUSH DATA', $request->all());

            try {

            
        $request->validate([
            'variant_platform_data' => 'required'
        ]);


            

                $data = json_decode($request->variant_platform_data, true);


                if (!$data || !is_array($data)) {
                    return back()->with('error', 'No platform data found');
                }

        DB::transaction(function () use ($data) {

            foreach ($data as $variantId => $platforms) {

            if (!is_array($platforms) || empty($platforms)) {
                continue; // skip empty variant
            }

        $variant = ProductVariant::with(['product','value'])
            ->where('id', $variantId)
            ->lockForUpdate()
            ->firstOrFail();

            $totalRequested = collect($platforms)
                ->filter(fn($p) => isset($p['qty']) && $p['qty'] > 0)
                ->sum('qty');

            if ($totalRequested <= 0) {
                continue;
            }
        // OLD allocation BEFORE update
        $oldAllocated = PlatformPricing::where('product_variant_id', $variantId)->sum('quantity');




        foreach ($platforms as $platformId => $p) {

            if (!isset($p['qty']) || $p['qty'] <= 0) continue;

            $platformProduct = PlatformProduct::firstOrCreate(
                [
                    'platform_id' => $platformId,
                    'product_id'  => $variant->product_id,
                ],
                [
                    'platform_sku'   => $variant->product->sku . ($variant->sku_suffix ?? ''),
                    'platform_price' => $p['price'],
                    'platform_stock' => 0,
                    'status'         => 'active',
                    'sync_status'    => 'pending',
                ]
            );

            $platformProduct->update([
                'platform_sku'   => $variant->product->sku . ($variant->sku_suffix ?? ''),
                'platform_price' => $p['price'],
                'status'         => 'active',
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
            ]
        );

        // ✅ ADD THIS
        $platformProduct->platform_stock = PlatformPricing::where('platform_product_id', $platformProduct->id)->sum('quantity');
        $platformProduct->save();

        }
        // NEW allocation AFTER update
        $newAllocated = PlatformPricing::where('product_variant_id', $variantId)->sum('quantity');

        $difference = $newAllocated - $oldAllocated;

        // ❗ Safety check
        if ($difference > 0 && $difference > $variant->quantity) {
            throw new \Exception(
                "Not enough stock for " .
                optional($variant->value)->value .
                " (Available: {$variant->quantity}, Needed: {$difference})"
            );
        }

        // ✅ Adjust stock
        if ($difference > 0) {
            $variant->decrement('quantity', $difference);
        }

        if ($difference < 0) {
            $variant->increment('quantity', abs($difference));
        }

        }

        });

        return redirect()->route('admin.products.list')
            ->with('success', 'Product pushed to marketplace successfully!');


            } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
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
        public function invoiceView(Product $product)
    {
        $product->load(['variants', 'supplier', 'warehouse']);

        return view('products.invoice', compact('product'));
    }
        public function deleteImage(Product $product, $index)
        {
            $images = is_array($product->gallery_images)
                ? $product->gallery_images
                : [];

            if (!isset($images[$index])) {
                return response()->json(['success' => false]);
            }

            // delete from S3
            Storage::disk('s3')->delete($images[$index]);

            // remove from array
            unset($images[$index]);
            $images = array_values($images);

            $product->update([
                'gallery_images' => $images
            ]);

            return response()->json(['success' => true]);
        }


}
