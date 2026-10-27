<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $orders = $this->orderService->getOrders($request);

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($orderId): JsonResponse
    {
        try {
            $order = $this->orderService->getOrder($orderId);

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, $orderId): JsonResponse
    {
        try {
            $result = $this->orderService->cancelOrder($orderId);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status']);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function track($orderId): JsonResponse
    {
        try {
            $trackingData = $this->orderService->trackOrder($orderId);

            return response()->json([
                'success' => true,
                'data' => $trackingData,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
