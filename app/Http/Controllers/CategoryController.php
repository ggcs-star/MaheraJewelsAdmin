<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CategoryController extends Controller
{

  public function index(Request $request)
{
    $categories = Category::with('children')
        ->whereNull('parent_id')
      ->when($request->filled('search'), function ($q) use ($request) {
    $search = $request->search;

    $q->where(function ($query) use ($search) {
        
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%")
              ->orWhereHas('children', function ($childQuery) use ($search) {
                  $childQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('slug', 'like', "%{$search}%");
              });
    });
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
        ->when(
    $request->filled('adv_field') && $request->filled('adv_value'),
    function ($q) use ($request) {

        $allowedFields = ['name', 'slug', 'status', 'visibility'];

        $field = $request->adv_field;
        $condition = $request->adv_condition;
        $value = $request->adv_value;

        if (!in_array($field, $allowedFields)) {
            return;
        }

       $q->where(function ($query) use ($field, $condition, $value) {

    if ($condition === 'like') {

        $query->where($field, 'LIKE', "%{$value}%")
              ->orWhereHas('children', function ($child) use ($field, $value) {
                  $child->where($field, 'LIKE', "%{$value}%");
              });

    } elseif ($condition === 'starts_with') {

        $query->where($field, 'LIKE', "{$value}%")
              ->orWhereHas('children', function ($child) use ($field, $value) {
                  $child->where($field, 'LIKE', "{$value}%");
              });

    } elseif ($condition === 'ends_with') {

        $query->where($field, 'LIKE', "%{$value}")
              ->orWhereHas('children', function ($child) use ($field, $value) {
                  $child->where($field, 'LIKE', "%{$value}");
              });

    } else {
        $query->where($field, $condition, $value)
              ->orWhereHas('children', function ($child) use ($field, $condition, $value) {
                  $child->where($field, $condition, $value);
              });
    }
});
}
)
        ->orderBy('sort_order')
        ->paginate(10);
        $categories->getCollection()->transform(function ($category, $index) use ($categories) {
    $category->serial = $categories->firstItem() + $index;
    return $category;
});


    $parents = Category::whereNull('parent_id')->get();

    return view('categories.index', compact('categories', 'parents'));
}
   public function create()
    {
        return view('catego.create', [
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

    $categoryName = $request->name;
    $parentId     = $request->parent_id;

    // slug safe folder names
    $categorySlug = Str::slug($categoryName);

   if ($parentId && $parent = Category::find($parentId)) {
    $parentSlug = Str::slug($parent->name);
    $path = "admin/category/{$parentSlug}/{$categorySlug}";
} else {
    $path = "admin/category/{$categorySlug}";
}


    return $request->file('image_url')->store($path, 's3');
}
   private function deleteImage(?string $imagePath): void
{
    if ($imagePath && Storage::disk('s3')->exists($imagePath)) {
        Storage::disk('s3')->delete($imagePath);
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
public function details(Category $category, Request $request)
{
    $category->load('parent', 'children');

    $serial = $request->serial;

    return view('categories.details', compact('category', 'serial'));
}


public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);
    
    if (empty($ids)) {
        return back()->with('error', 'No categories selected.');
    }
    $categoriesWithChildren = Category::whereIn('id', $ids)
        ->whereHas('children')
        ->pluck('id');
        
    if ($categoriesWithChildren->count() > 0) {
        return back()->with('error', 'Cannot delete categories that have subcategories.');
    }
    Category::whereIn('id', $ids)->delete();
    
    return redirect()
        ->to(admin_route('categories.index'))
        ->with('success', 'Selected categories deleted successfully.');
}

}
