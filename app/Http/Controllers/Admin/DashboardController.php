<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\AmazonOrder;
use App\Models\PlatformProduct;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Platform;
use App\Models\PlatformPricing;
use App\Models\PurchaseOrderItem;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use App\Models\AmazonOrderItem;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $totalWebsiteOrders = Order::count();
        $totalAmazonOrders = AmazonOrder::count();
        $totalOrders = $totalWebsiteOrders + $totalAmazonOrders;

        $currentMonthWebsiteSales = Order::where('status', 'delivered')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total');
        $currentMonthAmazonSales = AmazonOrder::where('order_status', 'Shipped')
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('order_total');
        $currentMonthSales = $currentMonthWebsiteSales + $currentMonthAmazonSales;
                $lastMonthWebsiteSales = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $lastMonthAmazonSales = AmazonOrder::where('order_status', 'Shipped')
            ->whereMonth('purchase_date', now()->subMonth()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('order_total');

        $lastMonthSales = $lastMonthWebsiteSales + $lastMonthAmazonSales;

        $salesGrowth = $lastMonthSales > 0 ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 1) : 0;

        $offlinePlatformId = Platform::where('display_name', 'Offline')->value('id');
        $websitePlatformId = Platform::where('display_name', 'Our Website')->value('id');
        $amazonPlatformId  = Platform::where('display_name', 'Amazon')->value('id');
        $productsInStock = 0;

        $variants = ProductVariant::all();

        foreach ($variants as $variant) {

            $websitePush = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function ($q) use ($websitePlatformId) {
                    $q->where('platform_id', $websitePlatformId);
                })
                ->sum('quantity');

            $offlinePush = PlatformProduct::where('platform_id', $offlinePlatformId)
                ->where('product_variant_id', $variant->id)
                ->sum('platform_stock');

            $amazonPush = PlatformProduct::where('platform_id', $amazonPlatformId)
                ->where('product_variant_id', $variant->id)
                ->sum('platform_stock');

            $websiteSold = OrderItem::where('variant_id', $variant->id)
                ->sum('quantity');

            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)
                ->sum('quantity');

            $amazonSold = AmazonOrderItem::where('product_variant_id', $variant->id)
                ->sum('quantity_ordered');

            $available =
                ($websitePush + $offlinePush + $amazonPush)
                - ($websiteSold + $offlineSold + $amazonSold);

            $productsInStock += max(0, $available);
        }

        $lowStockProducts = Product::with('variants')->get()->map(function ($product) use (
            $websitePlatformId,
            $offlinePlatformId,
            $amazonPlatformId
        ) {

            $available = 0;

            foreach ($product->variants as $variant) {

                $websitePush = PlatformPricing::where('product_variant_id', $variant->id)
                    ->whereHas('platformProduct', function ($q) use ($websitePlatformId) {
                        $q->where('platform_id', $websitePlatformId);
                    })
                    ->sum('quantity');

                $offlinePush = PlatformProduct::where('product_variant_id', $variant->id)
                    ->where('platform_id', $offlinePlatformId)
                    ->sum('platform_stock');

                $amazonPush = PlatformProduct::where('product_variant_id', $variant->id)
                    ->where('platform_id', $amazonPlatformId)
                    ->sum('platform_stock');

                $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');
                $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');
                $amazonSold = AmazonOrderItem::where('product_variant_id', $variant->id)->sum('quantity_ordered');

                $available += max(
                    0,
                    ($websitePush + $offlinePush + $amazonPush)
                    - ($websiteSold + $offlineSold + $amazonSold)
                );
            }

            $product->available_stock = $available;

            return $product;
        });

        $lowStockCount = $lowStockProducts->count();

        $recentWebsiteOrders = Order::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($order) {
                $order->is_amazon = false;
                return $order;
            });

        $recentAmazonOrders = AmazonOrder::with('items')
            ->orderByDesc('purchase_date')
            ->take(3)
            ->get()
            ->map(function($order) {
                $order->is_amazon = true;
                $order->order_number = $order->amazon_order_id;
                return $order;
            });

        $recentOrders = $recentWebsiteOrders->merge($recentAmazonOrders)
            ->sortByDesc(function($order) {
                return $order->created_at ?? $order->purchase_date;
            })
            ->take(6);

        $range = $request->get('range', '7');

        if ($range == '7') {
            $days = 7;
        } elseif ($range == '30') {
            $days = 30;
        } else {
            $days = 90;
        }

        $salesData = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');

            $websiteSales = Order::where('status', 'delivered')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');

            $amazonSales = AmazonOrder::where('order_status', 'Delivered')
                ->whereDate('purchase_date', $date->toDateString())
                ->sum('order_total');

            $salesData[] = $websiteSales + $amazonSales;
        }

        $orderStats = [
            'pending' => Order::where('status', 'pending')->count() + AmazonOrder::where('order_status', 'Pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count() + AmazonOrder::where('order_status', 'Shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count() + AmazonOrder::where('order_status', 'Delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count() + AmazonOrder::whereIn('order_status', ['Canceled', 'Cancelled'])->count(),
        ];

        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $websiteSales = Order::where('status', 'delivered')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('total');

            $amazonSales = AmazonOrder::where('order_status', 'Delivered')
                ->whereMonth('purchase_date', $i)
                ->whereYear('purchase_date', now()->year)
                ->sum('order_total');

            $monthlySalesData[] = $websiteSales + $amazonSales;
        }

        return view('dashboard.admin', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'currentMonthSales',
            'lastMonthSales',
            'salesGrowth',
            'lowStockProducts',
            'lowStockCount',
            'recentOrders',
            'orderStats',
            'monthlySalesData',
            'range',
            'salesData',
            'labels',
            'productsInStock',
        ));
    }

    public function getChartData(Request $request)
    {
        $range = $request->get('range', 7);

        if ($range == '7') {
            $days = 7;
        } elseif ($range == '30') {
            $days = 30;
        } else {
            $days = 90;
        }

        $labels = [];
        $salesData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');

            $websiteSales = Order::where('status', 'delivered')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');

            $amazonSales = AmazonOrder::where('order_status', 'Delivered')
                ->whereDate('purchase_date', $date->toDateString())
                ->sum('order_total');

            $salesData[] = $websiteSales + $amazonSales;
        }

        return response()->json([
            'success' => true,
            'labels' => $labels,
            'salesData' => $salesData
        ]);
    }
}