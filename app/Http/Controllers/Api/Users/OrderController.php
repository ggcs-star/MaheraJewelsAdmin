<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderController extends Controller
{
    /**
     * 5.1 Get User Orders
     */
   public function index(Request $request): JsonResponse
{
    try {
        $orders = Order::with('items:id,order_id,product_id,product_name,price,quantity,subtotal')
            ->where('user_id', auth()->id())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}

  

    /**
     * 5.2 Get Order Details
     */
    public function show($orderId): JsonResponse
    {
        try {
            $order = Order::with('items')
                ->where('user_id', auth()->id())
                ->findOrFail($orderId);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * 5.3 Cancel Order
     */
    public function cancel(Request $request, $orderId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $order = Order::where('user_id', auth()->id())
                ->findOrFail($orderId);

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled'
                ], 400);
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'status' => $order->status
                    ]
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * 5.4 Track Order
     */
    public function track($orderId): JsonResponse
    {
        try {
            $order = Order::where('user_id', auth()->id())
                ->findOrFail($orderId);

            return response()->json([
                'success' => true,
                'data' => [
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'tracking_number' => $order->tracking_number ?? null,
                    'estimated_delivery' => $order->estimated_delivery ?? null,
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
