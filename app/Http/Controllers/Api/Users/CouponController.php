<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Throwable;

class CouponController extends Controller
{
    public function index(): JsonResponse
    {
        $now = Carbon::now();

        $coupons = Coupon::with('bank:id,name')
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            })
            ->get()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code,
                    'coupon_type' => $coupon->coupon_type,
                    'bank' => $coupon->bank?->name,
                    'card_type' => $coupon->card_type,
                    'discount_type' => $coupon->discount_type,
                    'value' => $coupon->value,
                    'max_discount' => $coupon->max_discount, // ✅ Add this
                    'min_order_amount' => $coupon->min_order_amount,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }
    
    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'cart_total' => 'required|numeric', // ✅ Frontend se total lo
            'bank_id'     => 'nullable|integer',
            'card_type'   => 'nullable|in:credit,debit',
        ]);

        try {
            // ✅ Ab auth check nahi karte - Guest user allow
            // Cart database check HATAYA

            $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                ->where('is_active', true)
                ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or inactive coupon'
                ], 422);
            }

            $now = now();

            if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not started yet'
                ], 422);
            }

            if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon expired'
                ], 422);
            }

            // ✅ Frontend se aaya cart_total use karo
            $cartTotal = $request->cart_total;

            if ($coupon->min_order_amount && $cartTotal < $coupon->min_order_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order amount not met'
                ], 422);
            }
            
            // Bank coupon validation
            if ($coupon->coupon_type === 'BANK') {
                if (!$request->card_type) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Card type required for this coupon'
                    ], 422);
                }
                if ($coupon->bank_id) {
                    if (!$request->bank_id || $coupon->bank_id != $request->bank_id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Coupon not valid for selected bank'
                        ], 422);
                    }
                }

                if ($coupon->card_type !== 'both' && $coupon->card_type !== $request->card_type) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon not valid for this card type'
                    ], 422);
                }
            }

            // Discount calculation
            if ($coupon->discount_type === 'PERCENT') {
                $discount = ($cartTotal * $coupon->value) / 100;
                if ($coupon->max_discount) {
                    $discount = min($discount, $coupon->max_discount);
                }
            } else {
                $discount = $coupon->value;
            }

            $finalTotal = max($cartTotal - $discount, 0);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'data' => [
                    'cart_total' => round($cartTotal, 2),
                    'discount' => round($discount, 2),
                    'final_total' => round($finalTotal, 2),
                    'coupon_code' => $coupon->code,
                    'coupon_type' => $coupon->coupon_type
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon apply failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function remove(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed'
        ]);
    }
}