<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        
        $currentMonthSales = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        $lastMonthSales = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        $salesGrowth = $lastMonthSales > 0 ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 1) : 0;
        
        $lowStockProducts = Product::whereHas('variants')
            ->with(['variants'])
            ->take(10)
            ->get();
        
        $lowStockCount = Product::whereHas('variants')->count();
        
        $recentOrders = Order::latest()->take(6)->get();
        
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
            $salesData[] = Order::where('status', 'delivered')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');
        }
        
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[] = Order::where('status', 'delivered')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('total');
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
            'labels'
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
            $salesData[] = Order::where('status', 'delivered')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');
        }
        
        return response()->json([
            'success' => true,
            'labels' => $labels,
            'salesData' => $salesData
        ]);
    }
}