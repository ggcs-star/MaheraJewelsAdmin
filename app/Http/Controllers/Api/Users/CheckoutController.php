<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {
    }

    public function summary(Request $request): JsonResponse
    {
        return $this->checkoutService->summary($request);
    }

    public function placeOrder(Request $request): JsonResponse
    {
        return $this->checkoutService->placeOrder($request);
    }

    public function createRazorpayOrder(Request $request): JsonResponse
    {
        return $this->checkoutService->createRazorpayOrder($request);
    }

    public function verifyRazorpayPayment(Request $request): JsonResponse
    {
        return $this->checkoutService->verifyRazorpayPayment($request);
    }

    public function razorpayWebhook(Request $request)
    {
        return $this->checkoutService->razorpayWebhook($request);
    }
}
