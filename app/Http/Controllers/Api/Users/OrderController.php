<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Create Order
     */
    public function createOrder(Request $request)
    {

        $user = auth()->user();

        $cartItems = Cart::where('user_id',$user->id)->with('product')->get();

        if($cartItems->isEmpty()){
            return response()->json([
                'success'=>false,
                'message'=>'Cart is empty'
            ]);
        }

        DB::beginTransaction();

        try{

            $subtotal = 0;

            foreach($cartItems as $item){
                $subtotal += $item->price * $item->quantity;
            }

            $tax = $subtotal * 0.02;
            $shipping = 0;
            $discount = 0;

            $total = $subtotal + $tax + $shipping - $discount;

            $order = Order::create([
                'user_id'=>$user->id,
                'order_number'=>'ORD-'.time(),
                'subtotal'=>$subtotal,
                'tax'=>$tax,
                'shipping'=>$shipping,
                'discount'=>$discount,
                'total'=>$total,
                'status'=>'pending',
                'payment_status'=>'paid',
                'payment_method'=>'razorpay'
            ]);

            foreach($cartItems as $item){

                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$item->product_id,
                    'price'=>$item->price,
                    'quantity'=>$item->quantity,
                    'total'=>$item->price * $item->quantity
                ]);

            }

            Cart::where('user_id',$user->id)->delete();

            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Order created successfully',
                'data'=>$order
            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
        }

    }


    /**
     * User Orders List
     */
    public function orders()
    {

        $orders = Order::where('user_id',auth()->id())
        ->latest()
        ->paginate(10);

        return response()->json([
            'success'=>true,
            'data'=>$orders
        ]);

    }


    /**
     * Order Details
     */
    public function orderDetails($id)
    {

        $order = Order::with([
            'items.product'
        ])
        ->where('user_id',auth()->id())
        ->findOrFail($id);

        return response()->json([
            'success'=>true,
            'data'=>$order
        ]);

    }

}