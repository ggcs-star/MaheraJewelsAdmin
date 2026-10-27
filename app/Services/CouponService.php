<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CouponService
{
    public function getActiveCoupons(): Collection
    {
        $now = Carbon::now();

        return Coupon::with('bank:id,name')
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', $now);
            })
            ->get()
            ->map(fn ($coupon) => [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'code' => $coupon->code,
                'coupon_type' => $coupon->coupon_type,
                'bank' => $coupon->bank?->name,
                'card_type' => $coupon->card_type,
                'discount_type' => $coupon->discount_type,
                'value' => $coupon->value,
                'max_discount' => $coupon->max_discount,
                'min_order_amount' => $coupon->min_order_amount,
            ]);
    }

    public function applyCoupon(array $data): array
    {
        $coupon = Coupon::where(
            'code',
            strtoupper($data['coupon_code'])
        )
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return $this->failure('Invalid or inactive coupon');
        }

        if (
            $coupon->product_id &&
            $coupon->product_id != $data['product_id']
        ) {
            return $this->failure(
                'This coupon is valid only for selected product.'
            );
        }

        if (
            $coupon->subcategory_id &&
            $coupon->subcategory_id != $data['category_id']
        ) {
            return $this->failure(
                'This coupon is valid only for selected sub category.'
            );
        }

        if ($coupon->category_id) {
            $childIds = Category::where(
                'parent_id',
                $coupon->category_id
            )->pluck('id')->toArray();

            if (!in_array($data['category_id'], $childIds)) {
                return $this->failure(
                    'This coupon is valid only for selected category.'
                );
            }
        }

        $now = now();

        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return $this->failure('Coupon not started yet');
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            return $this->failure('Coupon expired');
        }

        $cartTotal = $data['cart_total'];

        if (
            $coupon->min_order_amount !== null &&
            $cartTotal < $coupon->min_order_amount
        ) {
            return $this->failure(
                'Minimum order amount of ₹' .
                number_format($coupon->min_order_amount, 2) .
                ' required'
            );
        }

        if ($coupon->coupon_type === 'BANK') {
            if (!$data['card_type']) {
                return $this->failure(
                    'Card type required for this coupon'
                );
            }

            if (
                $coupon->bank_id &&
                (
                    !$data['bank_id'] ||
                    $coupon->bank_id != $data['bank_id']
                )
            ) {
                return $this->failure(
                    'Coupon not valid for selected bank'
                );
            }

            if (
                $coupon->card_type !== 'both' &&
                $coupon->card_type !== $data['card_type']
            ) {
                return $this->failure(
                    'Coupon not valid for this card type'
                );
            }
        }

        if ($coupon->discount_type === 'PERCENT') {
            $discount = ($cartTotal * $coupon->value) / 100;

            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        } else {
            $discount = $coupon->value;
        }

        $finalTotal = max($cartTotal - $discount, 0);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'status' => 200,
            'data' => [
                'cart_total' => round($cartTotal, 2),
                'discount' => round($discount, 2),
                'final_total' => round($finalTotal, 2),
                'coupon_code' => $coupon->code,
                'coupon_type' => $coupon->coupon_type,
            ],
        ];
    }

    private function failure(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'status' => 422,
            'data' => [],
        ];
    }
}
