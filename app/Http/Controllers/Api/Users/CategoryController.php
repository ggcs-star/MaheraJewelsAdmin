<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService
                ->getActiveCategories();

            $data = CategoryResource::collection($categories)
                ->resolve();

            return $this->successResponse(
                'Categories fetched successfully',
                $data
            );
        } catch (Throwable $exception) {
            Log::error('Category List API Error', [
                'exception' => $exception,
            ]);

            return $this->errorResponse();
        }
    }

    public function show(int $category_id): JsonResponse
    {
        try {
            $category = $this->categoryService
                ->getActiveCategory($category_id);

            return $this->successResponse(
                'Category fetched successfully',
                (new CategoryResource($category))->resolve()
            );
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                'Category not found',
                404
            );
        } catch (Throwable $exception) {
            Log::error('Category Detail API Error', [
                'exception' => $exception,
                'category_id' => $category_id,
            ]);

            return $this->errorResponse();
        }
    }

    public function products(Category $category): JsonResponse
    {
        try {
            $data = $this->categoryService
                ->getCategoryProducts($category);

            return $this->successResponse(
                'Category products fetched successfully',
                $data
            );
        } catch (Throwable $exception) {
            Log::error('Category Products API Error', [
                'exception' => $exception,
                'category_id' => $category->getKey(),
            ]);

            return $this->errorResponse();
        }
    }
}
