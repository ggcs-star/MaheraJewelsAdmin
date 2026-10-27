<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\BannerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BannerController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly BannerService $bannerService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $page = $request->has('page')
                && in_array($request->input('page'), ['home', 'category', 'product'], true)
                    ? $request->input('page')
                    : null;

            $position = $request->has('position')
                && in_array($request->input('position'), ['hero', 'mid', 'bottom'], true)
                    ? $request->input('position')
                    : null;

            $banners = $this->bannerService->getBanners(
                $page,
                $position
            );

            return $this->successResponse(
                'Banners retrieved successfully',
                $banners
            );
        } catch (Throwable $exception) {
            Log::error('Banner List API Error', [
                'exception' => $exception,
            ]);

            return $this->errorResponse();
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $banner = $this->bannerService->findBanner($id);

            if (!$banner) {
                return $this->errorResponse(
                    'Banner not found',
                    404
                );
            }

            return $this->successResponse(
                'Banner retrieved successfully',
                $banner
            );
        } catch (Throwable $exception) {
            Log::error('Banner Detail API Error', [
                'exception' => $exception,
                'banner_id' => $id,
            ]);

            return $this->errorResponse();
        }
    }

    public function getByPage($page): JsonResponse
    {
        try {
            if (!in_array($page, ['home', 'category', 'product'], true)) {
                return $this->errorResponse(
                    'Invalid page type',
                    400
                );
            }

            $banners = $this->bannerService->getBannersByPage($page);

            return $this->successResponse(
                "Banners for {$page} page retrieved successfully",
                $banners
            );
        } catch (Throwable $exception) {
            Log::error('Banner Page API Error', [
                'exception' => $exception,
                'page' => $page,
            ]);

            return $this->errorResponse();
        }
    }

    public function getByPosition($position): JsonResponse
    {
        try {
            if (!in_array($position, ['hero', 'mid', 'bottom'], true)) {
                return $this->errorResponse(
                    'Invalid position type',
                    400
                );
            }

            $banners = $this->bannerService
                ->getBannersByPosition($position);

            return $this->successResponse(
                "Banners for {$position} position retrieved successfully",
                $banners
            );
        } catch (Throwable $exception) {
            Log::error('Banner Position API Error', [
                'exception' => $exception,
                'position' => $position,
            ]);

            return $this->errorResponse();
        }
    }
}
