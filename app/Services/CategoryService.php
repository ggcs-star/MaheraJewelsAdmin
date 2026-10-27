<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Platform;
use App\Transformers\ProductListTransformer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function getActiveCategories(): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->where('visibility', 'public')
            ->where('status', 'active')
            ->with([
                'children' => fn ($query) => $query
                    ->where('visibility', 'public')
                    ->where('status', 'active')
                    ->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();
    }

    public function getActiveCategory(int $categoryId): Category
    {
        return Category::query()
            ->whereKey($categoryId)
            ->where('visibility', 'public')
            ->where('status', 'active')
            ->firstOrFail();
    }

    public function getCategoryProducts(
        Category $category
    ): array {
        $platform = Platform::getOwnWebsite();

        $products = $category->products()
            ->leftJoin(
                'product_clicks',
                'products.id',
                '=',
                'product_clicks.product_id'
            )
            ->where('products.status', 'active')
            ->where('products.visibility', 'public')
            ->whereHas(
                'platformListings',
                fn ($query) => $query
                    ->where('platform_id', $platform->id)
                    ->userVisible()
            )
            ->with([
                'category:id,name',
                'variants.platformPricings' => fn ($query) => $query
                    ->where('status', 'active'),
            ])
            ->select(
                'products.*',
                DB::raw(
                    'COALESCE(product_clicks.click_count,0) as click_count'
                )
            )
            ->orderByDesc('click_count')
            ->paginate(12);

        return [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'products' => $products->getCollection()
                ->map(
                    fn ($product) => ProductListTransformer::transform($product)
                ),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
            ],
        ];
    }
}
