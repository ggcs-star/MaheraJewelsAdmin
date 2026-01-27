<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    /**
     * Display products list with search & filters
     */
    public function index(Request $request)
    {
        // Optimized query with select only needed columns
        $products = Product::select([
                'id', 'name', 'sku', 'slug', 'category_id', 'supplier_id',
                'cost_price', 'base_selling_price', 'image_url', 'status', 'visibility'
            ])
            ->with([
                'category:id,name',
                'supplier:id,name'
            ])

            // 🔍 Search (name, sku, slug)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })

            // 📂 Category filter
            ->when(
                $request->filled('category_id'),
                fn($q) => $q->where('category_id', $request->category_id)
            )

            // 🧑 Supplier filter
            ->when(
                $request->filled('supplier_id'),
                fn($q) => $q->where('supplier_id', $request->supplier_id)
            )

            // 👁 Visibility filter
            ->when(
                $request->filled('visibility'),
                fn($q) => $q->where('visibility', $request->visibility)
            )

            // ⚡ Status filter
            ->when(
                $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )

            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString(); // Preserve query parameters in pagination links

        // Optimize categories and suppliers loading - only get active ones
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

    public function show(Product $product)
    {
        // Load product with all relationships and variants
        $product->load([
            'category:id,name,slug,parent_id',
            'category.parent:id,name',
            'supplier:id,name,company_name,phone,email,type,commission_type,commission_value',
            'variants' => function($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }
        ]);

        return view('products.show', compact('product'));
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
                'total_price'    => ($variant['quantity'] ?? 0) * ($variant['purchase_price'] ?? 0),
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

    /**
     * Display products listing page (blank/empty state)
     */
    public function list()
    {
        return view('products.list');
    }

    /**
     * Show push product form
     */
    public function push()
    {
        // Get all products from inventory with their variants and category relationships
        $products = Product::with([
            'category:id,name,parent_id',
            'category.parent:id,name',
            'variants:id,product_id,variant_type,variant_value,sku_suffix,image_url,sort_order,status'
        ])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('products.push', [
            'products' => $products
        ]);
    }

    /**
     * Store pushed product (placeholder - will be implemented later)
     */
    public function pushStore(Request $request)
    {
        // TODO: Implement product push logic
        return redirect()
            ->to(admin_route('products.list'))
            ->with('success', 'Product pushed successfully to selected platforms.');
    }
}
