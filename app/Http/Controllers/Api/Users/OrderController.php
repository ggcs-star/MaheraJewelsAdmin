<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $orders = Order::with([
                'items' => function($query) {
                    $query->select('id', 'order_id', 'product_id', 'variant_id', 'product_name', 'price', 'quantity', 'subtotal', 'image');
                },
                'items.product:id,image_url,gallery_images',  // 🔥 gallery_images bhi lo
                'items.variant:id,image_url'
            ])
            ->where('user_id', auth()->id())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

            // Transform items to add image URLs
            $orders->getCollection()->transform(function ($order) {
                $order->items->transform(function ($item) {
                    
                    // Case 1: Direct item image
                    if ($item->image) {
                        if (!str_starts_with($item->image, 'http')) {
                            $item->image = Storage::disk('s3')->url($item->image);
                        }
                        return $item;
                    }
                    
                    // Case 2: Variant image
                    if ($item->variant && $item->variant->image_url) {
                        $item->image = Storage::disk('s3')->url($item->variant->image_url);
                        return $item;
                    }
                    
                    // Case 3: Product gallery image (priority)
                    if ($item->product && !empty($item->product->gallery_images)) {
                        $galleryImage = $item->product->gallery_images[0];
                        if (!str_starts_with($galleryImage, 'http')) {
                            $item->image = Storage::disk('s3')->url($galleryImage);
                        } else {
                            $item->image = $galleryImage;
                        }
                        return $item;
                    }
                    
                    // Case 4: Product image_url
                    if ($item->product && $item->product->image_url) {
                        $item->image = Storage::disk('s3')->url($item->product->image_url);
                        return $item;
                    }
                    
                    $item->image = null;
                    return $item;
                });
                
                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($orderId): JsonResponse
    {
        try {
            $order = Order::with([
                'items' => function($query) {
                    $query->select('id', 'order_id', 'product_id', 'variant_id', 'product_name', 'price', 'quantity', 'subtotal', 'image');
                },
                'items.product:id,image_url,gallery_images',  // 🔥 gallery_images bhi lo
                'items.variant:id,image_url'
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);
// Transform items to add image URLs
            $order->items->transform(function ($item) {
                
                // Case 1: Direct item image
                if ($item->image) {
                    if (!str_starts_with($item->image, 'http')) {
                        $item->image = Storage::disk('s3')->url($item->image);
                    }
                    return $item;
                }
                
                // Case 2: Variant image
                if ($item->variant && $item->variant->image_url) {
                    $item->image = Storage::disk('s3')->url($item->variant->image_url);
                    return $item;
                }
                
                // Case 3: Product gallery image (priority)
                if ($item->product && !empty($item->product->gallery_images)) {
                    $galleryImage = $item->product->gallery_images[0];
                    if (!str_starts_with($galleryImage, 'http')) {
                        $item->image = Storage::disk('s3')->url($galleryImage);
                    } else {
                        $item->image = $galleryImage;
                    }
                    return $item;
                }
                
                // Case 4: Product image_url
                if ($item->product && $item->product->image_url) {
                    $item->image = Storage::disk('s3')->url($item->product->image_url);
                    return $item;
                }
                
                $item->image = null;
                return $item;
            });

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
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

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
