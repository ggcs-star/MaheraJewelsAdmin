<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::with('parent', 'children')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('visibility'), function ($q) use ($request) {
                $q->where('visibility', $request->visibility);
            })
            ->when($request->filled('parent_id'), function ($q) use ($request) {
                $q->where('parent_id', $request->parent_id);
            })
            ->orderBy('sort_order')
            ->paginate(10);

        $parents = Category::whereNull('parent_id')->get();

        return view('categories.index', compact('categories', 'parents'));
    }



   public function create()
    {
        return view('categories.create', [
            'parents' => $this->parentCategories()
        ]);
    }

   
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['image_url'] = $this->uploadImage($request);

        Category::create($data);

        return redirect()
            ->to(admin_route('categories.index'))
            ->with('success', 'Category created successfully.');
    }

   
    public function edit(Category $category)
    {
        return view('categories.edit', [
            'category' => $category,
            'parents'  => $this->parentCategories($category->id),
        ]);
    }

   
    public function update(Request $request, Category $category)
    {
        $data = $this->validatedData($request, $category->id);

        if ($request->hasFile('image_url')) {
            $this->deleteImage($category->image_url);
            $data['image_url'] = $this->uploadImage($request);
        }

        $category->update($data);

        return redirect()
            ->to(admin_route('categories.index'))
            ->with('success', 'Category updated successfully.');
    }

    
    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        $this->deleteImage($category->image_url);

        $category->delete();

        return redirect()
            ->to(admin_route('categories.index'))
            ->with('success', 'Category deleted successfully.');
    }

   
    private function validatedData(Request $request, $categoryId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $categoryId,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',

            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',

            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
        ]);
    }

    
    private function uploadImage(Request $request): ?string
    {
        if (!$request->hasFile('image_url')) {
            return null;
        }

        return $request->file('image_url')
            ->store('categories', 'public');
    }

  
    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    
    private function parentCategories($excludeId = null)
    {
        return Category::whereNull('parent_id')
            ->when($excludeId, fn ($q) =>
                $q->where('id', '!=', $excludeId)
            )
            ->orderBy('name')
            ->get();
    }
}
