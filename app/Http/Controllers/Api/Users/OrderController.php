<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\StockMovement;
class OrderController extends Controller
{

    public function index(Request $request)
    {
        $query = Order::with(['user']);

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::with('items')->findOrFail($id);

        $currentStatus = strtolower(trim($order->status));
        $newStatus = strtolower(trim($request->status));

        if ($currentStatus === $newStatus) {
            return back()->with('error', 'Status is already ' . ucfirst($currentStatus));
        }

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
        if ($newStatus === 'confirmed') {

        foreach ($order->items as $item) {

            $variant = ProductVariant::find($item->variant_id);

            if ($variant) {

                $variant->decrement('quantity', $item->quantity);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'platform_id' => 1,
                    'movement' => 'OUT',
                    'quantity' => $item->quantity,
                    'balance' => $variant->quantity,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'remarks' => 'Order confirmed',
                ]);
            }
        }
    }

        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus));
    }


    public function invoice($id)
    {
        $order = Order::with([
            'items.product',
            'items.variant',
            'user',
            'shippingAddress',
            'payment'
        ])->findOrFail($id);

        $company = Organization::first();

        return view('admin.orders.invoice', compact('order', 'company'));
    }
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