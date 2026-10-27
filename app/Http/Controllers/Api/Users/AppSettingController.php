<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\AppSettingService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppSettingController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AppSettingService $appSettingService
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $setting = $this->appSettingService->getActiveSetting();

            if (!$setting) {
                return $this->errorResponse(
                    'App settings not found',
                    404
                );
            }

            return $this->successResponse(
                'App settings fetched successfully',
                [
                    'app_name' => $setting->app_name,
                    'app_logo' => $setting->app_logo_url,
                    'splash_logo' => $setting->splash_logo_url,
                    'header_logo' => $setting->header_logo_url,
                    'is_active' => $setting->is_active,
                ]
            );
        } catch (Throwable $exception) {
            Log::error('App Setting API Error', [
                'exception' => $exception,
            ]);

            return $this->errorResponse();
        }
    }
}
