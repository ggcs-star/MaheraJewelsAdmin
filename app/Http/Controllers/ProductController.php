<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products list with search & filters
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])

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
            ->when($request->filled('category_id'), fn ($q) =>
                $q->where('category_id', $request->category_id)
            )

            // 🧑 Supplier filter
            ->when($request->filled('supplier_id'), fn ($q) =>
                $q->where('supplier_id', $request->supplier_id)
            )

            // 👁 Visibility filter
            ->when($request->filled('visibility'), fn ($q) =>
                $q->where('visibility', $request->visibility)
            )

            // ⚡ Status filter
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )

            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('products.index', [
            'products'   => $products,
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

      public function create()
    {
        return view('products.create', [
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
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
        'cost_price' => 'required|numeric|min:0',
        'base_selling_price' => 'required|numeric|min:0',

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

    // Main image
    if ($request->hasFile('image_url')) {
        $data['image_url'] = $request->file('image_url')
            ->store('products', 'public');
    }

    // Gallery images
    if ($request->hasFile('gallery_images')) {
        $gallery = [];

        foreach ($request->file('gallery_images') as $image) {
            $gallery[] = $image->store('products/gallery', 'public');
        }

        $data['gallery_images'] = $gallery; // 👈 IMPORTANT
    }

    Product::create($data);

    return redirect()
        ->to(admin_route('products.index'))
        ->with('success', 'Product created successfully.');
}

}
