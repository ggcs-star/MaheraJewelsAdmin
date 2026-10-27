<?php

namespace App\Services;

use App\Helpers\S3Helper;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getOrders(Request $request)
    {
        $orders = Order::with([
            'items' => function ($query) {
                $query->select(
                    'id',
                    'order_id',
                    'product_id',
                    'variant_id',
                    'product_name',
                    'price',
                    'quantity',
                    'subtotal',
                    'image'
                );
            },
            'items.product:id,image_url,gallery_images',
            'items.variant:id,image_url',
        ])
            ->where('user_id', auth()->id())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        $orders->getCollection()->transform(function ($order) {
            $order->items->transform(function ($item) {
                if (
                    $item->image &&
                    !str_starts_with($item->image, 'http')
                ) {
                    $item->image = S3Helper::url($item->image);
                }

                return $item;
            });

            return $order;
        });

        return $orders;
    }

    public function getOrder($orderId)
    {
        $order = Order::with([
            'items' => function ($query) {
                $query->select(
                    'id',
                    'order_id',
                    'product_id',
                    'variant_id',
                    'product_name',
                    'price',
                    'quantity',
                    'subtotal',
                    'image'
                );
            },
            'items.product:id,image_url,gallery_images',
            'items.variant:id,image_url',
        ])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        $order->items->transform(function ($item) {
            if (
                $item->image &&
                !str_starts_with($item->image, 'http')
            ) {
                $item->image = S3Helper::url($item->image);
            }

            return $item;
        });

        return $order;
    }

    public function cancelOrder($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::where('user_id', auth()->id())
                ->findOrFail($orderId);

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return [
                    'success' => false,
                    'message' => 'Order cannot be cancelled',
                    'status' => 400,
                ];
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'status' => $order->status,
                    ],
                ],
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function trackOrder($orderId)
    {
        $order = Order::where('user_id', auth()->id())
            ->findOrFail($orderId);

        return [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'tracking_number' => $order->tracking_number ?? null,
            'estimated_delivery' => $order->estimated_delivery ?? null,
        ];
    }
}
