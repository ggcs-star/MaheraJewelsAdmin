<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Payment;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        try {
            $cart = Cart::where('user_id', auth()->id())
                ->with(['items.product'])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 422);
            }

            $subtotal = $cart->items->sum('subtotal');
            $tax = 0;
            $shipping = 0;
            $discount = $this->calculateDiscount($request->coupon_code, $subtotal);
            $total = max(
                round($subtotal - $discount, 2),
                0
            );

            $addresses = UserAddress::where('user_id', auth()->id())
                ->where('type', 'shipping')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => [
                        'currency' => 'INR',
                        'subtotal' => round($subtotal, 2),
                        'tax' => $tax,
                        'shipping' => $shipping,
                        'discount' => round($discount, 2),
                        'total' => $total,
                        'items_count' => $cart->items->sum('quantity'),
                        'coupon_code' => $request->coupon_code ?? null,
                    ],
                    'shipping_addresses' => $addresses,
                    'payment_methods' => [
                        [
                            'id' => 1,
                            'name' => 'Cash on Delivery',
                            'type' => 'cod',
                            'is_available' => true,
                        ],
                        [
                            'id' => 2,
                            'name' => 'Online Payment',
                            'type' => 'online',
                            'is_available' => true,
                        ],
                    ],
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to load checkout summary'
            ], 500);
        }
    }

    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address_id' => 'required|integer',
            'billing_address_id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'coupon_code' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();

            $cart = Cart::where('user_id', $userId)
                ->with(['items.product'])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                abort(422, 'Cart is empty');
            }

            $subtotal = $cart->items->sum('subtotal');
            $tax = 0;
            $shipping = 0;
            $discount = $this->calculateDiscount($request->coupon_code, $subtotal);

            $total = max(
                round($subtotal - $discount, 2),
                0
            );
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . rand(100, 999),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => round($subtotal, 2),
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => round($discount, 2),
                'total' => $total,
                'coupon_code' => $request->coupon_code,
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'payment_method_id' => $request->payment_method_id,
            ]);
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'variant_id' => $item->product_variant_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
            }

            
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'total' => $order->total,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                    ]
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    private function calculateDiscount(?string $couponCode, float $subtotal): float
    {
        if (!$couponCode) {
            return 0;
        }

        $coupon = Coupon::where('code', strtoupper($couponCode))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return 0;
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return 0;
        }

        if ($coupon->discount_type === 'PERCENT') {
            $discount = ($subtotal * $coupon->value) / 100;

            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        } else {
            $discount = $coupon->value;
        }

        $discount = min($discount, $subtotal);

        return round($discount, 2);
    }
    public function createRazorpayOrder(Request $request): JsonResponse
    {
      $request->validate([
        'shipping_address_id' => 'required|integer',
        'billing_address_id' => 'required|integer',
        'payment_method_id' => 'required|integer|in:2', 
        'coupon_code' => 'nullable|string',
    ]);
    try {
        $userId = auth()->id();

        $cart = Cart::where('user_id', $userId)
            ->with(['items.product'])
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 422);
        }

        $subtotal = $cart->items->sum('subtotal');
        $tax = 0;
        $shipping = 0;
         $discount = $this->calculateDiscount(
            $request->coupon_code,
            $subtotal
        );
        $total = max(
            round($subtotal - $discount, 2),
            0
        );
    

        $payment = Payment::create([
            'user_id' => $userId,
            'amount' => (float) $total,
            'currency' => 'INR',
            'payment_method' => 'razorpay',
            'payment_status' => 'pending',
            'coupon_code' => $request->coupon_code,
            'payment_meta' => [
            'shipping_address_id' => $request->shipping_address_id,
            'billing_address_id' => $request->billing_address_id,
        ],
        ]);

        $razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $razorpay->order->create([
            'receipt'  => 'pay_' . $payment->id,
            'amount'   => (int) round($total * 100), 
            'currency' => 'INR',
        ]);

        $payment->update([
            'razorpay_order_id' => $razorpayOrder['id']
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'payment_id' => $payment->id,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => (float) $total,
                'currency' => 'INR',
            ]
        ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
        }
    public function verifyRazorpayPayment(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $userId = auth()->id();

            $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('user_id', $userId)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
            $cart = Cart::where('user_id', $userId)
                ->with(['items.product'])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $cart->items->sum('subtotal');
            $tax = 0;
            $shipping = 0;
            $discount = $this->calculateDiscount($payment->coupon_code ?? null,$subtotal);
            $total = max(
                round($subtotal - $discount, 2),
                0
            );
        
            $meta = $payment->payment_meta;

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'subtotal' => round($subtotal, 2),
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'shipping_address_id' => $meta['shipping_address_id'],
                'billing_address_id' => $meta['billing_address_id'],
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
            }

            $payment->update([
                'order_id' => $order->id,
                'payment_status' => 'paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'paid_at' => now(),
            ]);

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified & order placed',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]
            ]);

        } catch (SignatureVerificationError $e) {
            DB::rollBack();

            if (isset($payment)) {
                $payment->update(['payment_status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);

        } catch (Throwable $e) {
            DB::rollBack();

            if (isset($payment)) {
                $payment->update(['payment_status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
