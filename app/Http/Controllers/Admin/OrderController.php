<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Orders List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $query = Order::with(['user']);

        // Search Order Number
        if ($request->filled('search')) {

            $query->where('order_number', 'like', '%' . $request->search . '%');

        }

        // Filter Status
        if ($request->filled('status')) {

            $query->where('status', $request->status);

        }

        $orders = $query->latest()->paginate(10);


        // Stats
        $stats = [

            'total' => Order::count(),

            'pending' => Order::where('status', 'pending')->count(),

            'processing' => Order::where('status', 'processing')->count(),

            'shipped' => Order::where('status', 'shipped')->count(),

            'delivered' => Order::where('status', 'delivered')->count(),

            'cancelled' => Order::where('status', 'cancelled')->count(),

        ];


        return view('admin.orders.index', compact('orders', 'stats'));

    }



    /*
    |--------------------------------------------------------------------------
    | Order Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $order = Order::with([

            'items.product',
            'items.variant',
            'user',
            'shippingAddress',
            'billingAddress',
            'payment'

        ])->findOrFail($id);


        return view('admin.orders.show', compact('order'));

    }



    /*
    |--------------------------------------------------------------------------
    | Update Order Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {

        $request->validate([

            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'

        ]);


        $order = Order::findOrFail($id);


        $currentStatus = $order->status;
        $newStatus = $request->status;


        // Allowed Status Flow
        $allowedTransitions = [

            'pending' => ['confirmed', 'cancelled'],

            'confirmed' => ['processing', 'cancelled'],

            'processing' => ['shipped'],

            'shipped' => ['delivered'],

        ];


        if (

            isset($allowedTransitions[$currentStatus]) &&

            !in_array($newStatus, $allowedTransitions[$currentStatus])

        ) {

            return back()->with('error', 'Invalid status transition');

        }


        $order->status = $newStatus;


        // Time Tracking
        if ($newStatus === 'confirmed') {

            $order->confirmed_at = now();

        }

        if ($newStatus === 'shipped') {

            $order->shipped_at = now();

        }

        if ($newStatus === 'delivered') {

            $order->delivered_at = now();

        }


        $order->save();


        return back()->with('success', 'Order status updated successfully');

    }



    /*
    |--------------------------------------------------------------------------
    | Print Invoice
    |--------------------------------------------------------------------------
    */

    public function invoice($id)
    {

        $order = Order::with([

            'items.product',
            'items.variant',
            'user',
            'shippingAddress',
            'payment'

        ])->findOrFail($id);


        return view('admin.orders.invoice', compact('order'));

    }



    /*
    |--------------------------------------------------------------------------
    | Cancel Order
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {

        $order = Order::findOrFail($id);


        if ($order->status === 'delivered') {

            return back()->with('error', 'Delivered order cannot be cancelled');

        }


        $order->status = 'cancelled';

        $order->save();


        return back()->with('success', 'Order cancelled successfully');

    }

}