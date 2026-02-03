<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PlatformProduct;
use App\Models\PlatformPricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();


            $cart = Cart::where('user_id', $user->id)
                ->with([
                    'items.product:id,name,image_url',
                    'items.variant:id,variant_type,variant_value',
                ])
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'cart_id' => null,
                        'items' => [],
                        'cart_total' => 0,
                        'items_count' => 0,
                    ]
                ]);
            }


            $items = $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'platform_id' => $item->platform_id,

                    'product_name' => $item->product?->name,
                    'variant' => [
                        'type' => $item->variant?->variant_type,
                        'value' => $item->variant?->variant_value,
                    ],

                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'image_url' => $item->product?->image_url,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'cart_id' => $cart->id,
                    'items' => $items,
                    'cart_total' => $cart->items()->sum('subtotal'),
                    'items_count' => $cart->items()->sum('quantity'),
                ]
            ]);

        } catch (Throwable $e) {

            Log::error('Get Cart API Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch cart details'
            ], 500);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $user = $request->user();


            $platformProduct = PlatformProduct::query()
                ->where('product_id', $request->product_id)
                ->userVisible()
                ->first();

            if (!$platformProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not available on this platform'
                ], 404);
            }


            $pricing = PlatformPricing::query()
                ->where('platform_product_id', $platformProduct->id)
                ->where('product_variant_id', $request->variant_id)
                ->where('status', 'active')
                ->first();

            if (!$pricing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product pricing not available'
                ], 404);
            }

            if ($pricing->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity not available'
                ], 422);
            }

            $unitPrice = $pricing->final_price ?? $pricing->price;

            DB::beginTransaction();


            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
            ]);


            $cartItem = CartItem::where([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'platform_id' => $platformProduct->platform_id,
            ])->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
            } else {
                $cartItem = new CartItem([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'product_variant_id' => $request->variant_id,
                    'platform_id' => $platformProduct->platform_id,
                    'price' => $unitPrice,
                    'quantity' => 0,
                ]);
            }

            $cartItem->price = $unitPrice;
            $cartItem->subtotal = $cartItem->quantity * $unitPrice;
            $cartItem->save();


            $cartTotal = $cart->items()->sum('subtotal');
            $itemsCount = $cart->items()->sum('quantity');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => [
                    'cart_item' => [
                        'id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'variant_id' => $cartItem->product_variant_id,
                        'platform_id' => $cartItem->platform_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'subtotal' => $cartItem->subtotal,
                    ],
                    'cart_total' => $cartTotal,
                    'items_count' => $itemsCount,
                ]
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Add to Cart API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }


}
