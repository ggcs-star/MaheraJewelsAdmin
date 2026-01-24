@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1">Welcome back, here's what's happening today.</p>
        </div>
        <div class="flex gap-2">
            <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export CSV
            </button>
            <button class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </button>
        </div>
    </div>

   
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['title' => 'Total Products', 'value' => '1,284', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'gradient' => 'from-blue-500 to-indigo-600'],
                ['title' => 'Total Categories', 'value' => '24', 'icon' => 'M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01M19 7h.01M19 11h.01M19 15h.01M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z', 'gradient' => 'from-purple-500 to-indigo-600'],
                ['title' => 'Low Stock Items', 'value' => '12', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'gradient' => 'from-orange-500 to-red-600'],
                ['title' => 'Total Orders', 'value' => '458', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'gradient' => 'from-emerald-500 to-teal-600'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $stat['gradient'] }} flex items-center justify-center text-white shadow-lg">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Recent Products</h3>
                <a href="#" class="text-indigo-600 text-sm font-semibold hover:underline underline-offset-4">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Product Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $recentProducts = [
                                ['id' => '1', 'name' => 'Premium Wireless Headphones', 'category' => 'Electronics', 'stock' => 45, 'status' => 'In Stock'],
                                ['id' => '2', 'name' => 'Ergonomic Desk Chair', 'category' => 'Furniture', 'stock' => 8, 'status' => 'Low Stock'],
                                ['id' => '3', 'name' => 'Mechanical Keyboard RGB', 'category' => 'Electronics', 'stock' => 0, 'status' => 'Out of Stock'],
                                ['id' => '4', 'name' => 'USB-C Fast Charger 65W', 'category' => 'Accessories', 'stock' => 120, 'status' => 'In Stock'],
                                ['id' => '5', 'name' => 'Smart Fitness Watch', 'category' => 'Electronics', 'stock' => 3, 'status' => 'Low Stock'],
                                ['id' => '6', 'name' => 'Leather Messenger Bag', 'category' => 'Apparel', 'stock' => 12, 'status' => 'In Stock'],
                            ];
                        @endphp
                        @foreach($recentProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-gray-900 truncate max-w-[150px]">{{ $product['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $product['category'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ $product['stock'] }} Units</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($product['status']) {
                                            'In Stock' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'Low Stock' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Out of Stock' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                        {{ $product['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

       
        <div class="space-y-6">
            
            <div class="bg-gradient-to-br from-indigo-700 to-purple-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-8 -bottom-8 opacity-20 group-hover:scale-110 transition-transform duration-700">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm0 3.45L19.45 19H4.55L12 5.45zM11 16h2v2h-2v-2zm0-7h2v5h-2V9z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="font-bold">AI Stock Analyst</h4>
                    </div>
                    <p class="text-indigo-100 text-sm leading-relaxed mb-6">
                        "Based on current trends, <span class="text-white font-bold">Electronics</span> demand is expected to surge by 15% next week. Consider restocking item <span class="underline">#294</span>."
                    </p>
                    <button class="w-full py-2.5 bg-white text-indigo-700 rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-50 transition-colors">
                        View Smart Forecast
                    </button>
                </div>
            </div>

           
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Quick Shortcuts</h3>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $actions = [
                            ['label' => 'Stock Scan', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                            ['label' => 'Price Sync', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Bulk Edit', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            ['label' => 'Supplier Log', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                        ];
                    @endphp
                    @foreach($actions as $action)
                        <button class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition-all group">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}" />
                            </svg>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-indigo-700 text-center">{{ $action['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection