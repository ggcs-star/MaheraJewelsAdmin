@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pushed Products</h1>
            <p class="text-gray-600 mt-2">Products that have been synced to external platforms and marketplaces</p>
        </div>

        <a href="{{ admin_route('products.push') }}"
           class="inline-flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-6 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Push New Product
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 rounded-xl shadow-md flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($pushedProducts->count())

    @php
        $totalProducts = $pushedProducts->count();
        $totalStock = $pushedProducts->sum('platform_stock');
        $totalValue = $pushedProducts->sum(fn($item) =>
            ($item->platform_stock ?? 0) * ($item->platform_price ?? 0)
        );
    @endphp

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-2xl border border-green-100 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 mb-1">Total Stock</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalStock) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-2xl border border-purple-100 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 mb-1">Total Value</p>
                    <p class="text-3xl font-bold text-gray-900">₹{{ number_format($totalValue,2) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Product List</h2>
                <span class="text-sm text-gray-500">{{ $pushedProducts->count() }} items</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Product</th>
                        <th class="text-left p-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Platform</th>
                        <th class="text-left p-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Stock</th>
                        <th class="text-left p-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Value</th>
                        <th class="text-left p-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-100">
                @foreach($pushedProducts as $item)
                @php
                    $totalStock = $item->platform_stock ?? 0;
                    $totalValue = $totalStock * ($item->platform_price ?? 0);
                @endphp

                <tbody x-data="{ open: false }">
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <!-- PRODUCT CELL -->
                        <td class="p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 line-clamp-1">{{ $item->product->name }}</div>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $item->product->category->name ?? 'Uncategorized' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $item->product->supplier->name ?? 'No Supplier' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- PLATFORM CELL -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-100 to-pink-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">{{ $item->platform->display_name }}</span>
                            </div>
                        </td>

                        <!-- STOCK CELL -->
                        <td class="p-4">
                            <button @click="open = !open"
                                class="group flex items-center gap-3 bg-gradient-to-r from-sky-50 to-blue-50 hover:from-sky-100 hover:to-blue-100 border border-blue-200 text-blue-800 font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 hover:border-blue-300 hover:shadow-sm">
                                <span class="text-lg">{{ $totalStock }}</span>
                                <span class="text-sm font-normal">units</span>
                                <svg class="w-4 h-4 ml-2 transition-transform duration-200 group-hover:translate-y-0.5"
                                     :class="open ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </td>

                        <!-- VALUE CELL -->
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-bold text-emerald-700 text-lg">₹{{ number_format($totalValue,2) }}</span>
                            </div>
                        </td>

                        <!-- STATUS CELL -->
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800 border-green-200',
                                    'inactive' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'synced' => 'bg-blue-100 text-blue-800 border-blue-200',
                                ];
                                $statusClass = $statusColors[strtolower($item->status)] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusClass }}">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>

                    <!-- DROPDOWN VARIANTS SECTION -->
                    <tr x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2">
                        <td colspan="5" class="p-0">
                            <div class="bg-gradient-to-b from-blue-50/50 to-white px-4 pb-4">
                                <div class="bg-white rounded-xl shadow-sm border border-blue-100 overflow-hidden">
                                    <div class="px-5 py-3.5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <h3 class="font-semibold text-gray-800">Variant Breakdown</h3>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $item->product->variants->count() }} variants</span>
                                        </div>
                                    </div>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="text-left p-3 font-medium text-gray-700 text-sm">Variant</th>
                                                    <th class="text-center p-3 font-medium text-gray-700 text-sm">Quantity</th>
                                                    <th class="text-center p-3 font-medium text-gray-700 text-sm">Unit Price</th>
                                                    <th class="text-center p-3 font-medium text-gray-700 text-sm">Total Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach($item->product->variants as $variant)
                                                <tr class="hover:bg-gray-50/50 transition-colors">
                                                    <td class="p-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-50 rounded-lg flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-gray-900">{{ $variant->variant_type }}</div>
                                                                <div class="text-sm text-gray-500">{{ $variant->variant_value }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center p-3">
                                                        <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-700 font-semibold rounded-lg">
                                                            {{ $variant->quantity }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center p-3">
                                                        <div class="text-gray-600 font-medium">₹{{ number_format($item->platform_price,2) }}</div>
                                                    </td>
                                                    <td class="text-center p-3">
                                                        <div class="font-bold text-emerald-700">
                                                            ₹{{ number_format($variant->quantity * $item->platform_price,2) }}
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-8">
        {{ $pushedProducts->links() }}
    </div>

    @else
    <!-- EMPTY STATE -->
    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Products Pushed Yet</h3>
        <p class="text-gray-500 max-w-md mb-8">Start by pushing your first product to external platforms to see them listed here.</p>
        <a href="{{ admin_route('products.push') }}"
           class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-6 py-3 rounded-xl shadow hover:shadow-lg transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Push Your First Product
        </a>
    </div>
    @endif

</div>

<!-- Add custom animations -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>
@endsection