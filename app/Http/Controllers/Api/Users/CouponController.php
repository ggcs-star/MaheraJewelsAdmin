<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CouponController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CouponService $couponService
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $coupons = $this->couponService->getActiveCoupons();

            return $this->successResponse(
                'Coupons fetched successfully',
                $coupons
            );
        } catch (Throwable $exception) {
            Log::error('Coupon List API Error', [
                'exception' => $exception,
            ]);

            return $this->errorResponse();
        }
    }

    public function apply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'coupon_code' => 'required|string',
            'cart_total' => 'required|numeric',
            'product_id' => 'required|integer',
            'category_id' => 'required|integer',
            'bank_id' => 'nullable|integer',
            'card_type' => 'nullable|in:credit,debit',
        ]);

        try {
            $data['bank_id'] = $data['bank_id'] ?? null;
            $data['card_type'] = $data['card_type'] ?? null;

            $result = $this->couponService->applyCoupon($data);

            if (!$result['success']) {
                return $this->errorResponse(
                    $result['message'],
                    $result['status']
                );
            }

            return $this->successResponse(
                $result['message'],
                $result['data'],
                $result['status']
            );
        } catch (Throwable $exception) {
            Log::error('Coupon Apply API Error', [
                'exception' => $exception,
            ]);

            return $this->errorResponse();
        }
    }

    public function remove(): JsonResponse
    {
        return $this->successResponse('Coupon removed');
    }
}
