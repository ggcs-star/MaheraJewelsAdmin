@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6"
     x-data="{ openPlatform: null, openProduct: null }">

    <!-- HEADER -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Control Center</h1>
                <p class="text-gray-600 mt-1">Products that have been synced to external platforms and marketplaces</p>
            </div>
            <a href="{{ admin_route('products.push') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Push Product
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 animate-fade-in">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif
    </div>

    @php
        $platformGroups = [];
        $totalProducts = 0;
        $totalStock = 0;
        $totalValue = 0;
        
        foreach($pushedProducts as $item) {
            $platformId = $item->platform_id;
            if (!isset($platformGroups[$platformId])) {
                $platformGroups[$platformId] = [
                    'platform' => $item->platform,
                    'items' => [],
                    'platform_stock' => 0,
                    'platform_value' => 0,
                    'platform_product_count' => 0
                ];
            }
            
            $itemStock = $item->pricing->sum('quantity');
            $itemValue = $item->pricing->sum(fn($p) => $p->quantity * $p->final_price);
            
            $platformGroups[$platformId]['items'][] = $item;
            $platformGroups[$platformId]['platform_stock'] += $itemStock;
            $platformGroups[$platformId]['platform_value'] += $itemValue;
            $platformGroups[$platformId]['platform_product_count']++;
            
            $totalStock += $itemStock;
            $totalValue += $itemValue;
        }
        
        $totalProducts = $pushedProducts->count();
    @endphp

    <!-- STATS OVERVIEW -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: var(--primary-bg);">
                    <svg class="w-6 h-6" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(16, 185, 129, 0.1);">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStock) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(139, 36, 82, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Value</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($pushedProducts->count())
    <!-- MAIN TABLE -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Platform & Products</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Value</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($platformGroups as $platformId => $platformGroup)
                    @php
                        $platform = $platformGroup['platform'];
                        $platformItems = $platformGroup['items'];
                        $platformStock = $platformGroup['platform_stock'];
                        $platformValue = $platformGroup['platform_value'];
                        $platformProductCount = $platformGroup['platform_product_count'];
                    @endphp

                    <!-- PLATFORM ROW -->
                    <tr class="cursor-pointer transition-colors" style="background: var(--primary-bg); hover:bg-opacity-20"
                        @click="openPlatform === {{ $platformId }} ? (openPlatform = null, openProduct = null) : (openPlatform = {{ $platformId }}, openProduct = null)">
                        <td colspan="4" class="p-0">
                            <div class="py-4 px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $platformName = strtolower(trim($platform->display_name));
                                        @endphp
                                        <div class="w-16 h-10 flex items-center">
                                            @if(str_contains($platformName,'amazon'))
                                                <svg viewBox="0 0 200 60" class="h-6 w-auto" preserveAspectRatio="xMidYMid meet">
                                                    <text x="0" y="42" font-size="38" font-weight="700" fill="#111" font-family="Arial, Helvetica, sans-serif">amazon</text>
                                                    <path d="M10 50 C40 70, 120 70, 150 50" stroke="#FF9900" stroke-width="5" fill="none" stroke-linecap="round"/>
                                                </svg>
                                            @elseif(str_contains($platformName,'flipkart'))
                                                <svg viewBox="0 0 64 64" class="h-8 w-auto" preserveAspectRatio="xMidYMid meet">
                                                    <rect width="64" height="64" rx="14" fill="#2874F0"/>
                                                    <text x="32" y="44" text-anchor="middle" font-size="40" font-weight="800" fill="#FFD700" font-family="Arial, Helvetica, sans-serif">F</text>
                                                </svg>
                                            @elseif(str_contains($platformName,'website'))
                                                <span class="text-xl leading-none">🌐</span>
                                            @else
                                                <div class="w-8 h-8 rounded-full" style="background: var(--primary-bg);"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $platform->display_name }}</h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-600 bg-white px-2 py-0.5 rounded border">{{ $platformProductCount }} product(s)</span>
                                                <span class="text-xs text-gray-600 bg-white px-2 py-0.5 rounded border">{{ $platformStock }} units</span>
                                                <span class="text-xs font-medium text-emerald-600 bg-white px-2 py-0.5 rounded border">₹{{ number_format($platformValue, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">
                                            <span x-show="openPlatform !== {{ $platformId }}" x-cloak>Expand</span>
                                            <span x-show="openPlatform === {{ $platformId }}" x-cloak>Collapse</span>
                                        </span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                             :class="openPlatform === {{ $platformId }} ? 'rotate-180' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- PRODUCTS FOR THIS PLATFORM -->
                    <tr x-show="openPlatform === {{ $platformId }}" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <td colspan="4" class="p-0 bg-white">
                            <div class="border-t border-gray-100">
                                <table class="w-full">
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($platformItems as $item)
                                        @php
                                            $itemStock = $item->pricing->sum('quantity');
                                            $itemValue = $item->pricing->sum(fn($p) => $p->quantity * $p->final_price);
                                        @endphp

                                        <!-- PRODUCT ROW -->
                                        <tr class="hover:bg-gray-50 cursor-pointer transition-colors"
                                            @click="openProduct === {{ $item->id }} ? openProduct = null : openProduct = {{ $item->id }}">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg border flex items-center justify-center flex-shrink-0" style="background: var(--primary-bg); border-color: rgba(139,36,82,0.2);">
                                                        <svg class="w-5 h-5" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                {{ $item->product->category->name ?? 'Uncategorized' }}
                                                            </span>
                                                            <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                {{ $item->product->supplier->name ?? 'No Supplier' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 align-top">
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-semibold" style="color: var(--primary-light);">{{ $itemStock }}</span>
                                                    <span class="text-xs text-gray-500">units</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 align-top">
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-semibold text-emerald-700">₹{{ number_format($itemValue, 2) }}</span>
                                                    <span class="text-xs text-gray-500">value</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 align-top">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'bg-emerald-100 text-emerald-800',
                                                        'inactive' => 'bg-gray-100 text-gray-800',
                                                        'pending' => 'bg-amber-100 text-amber-800',
                                                        'synced' => 'bg-indigo-100 text-indigo-800',
                                                    ];
                                                    $statusClass = $statusColors[strtolower($item->status)] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ ucfirst($item->platform->pivot->status ?? $item->status ?? 'synced') }}
                                                </span>
                                            </td>
                                        </tr>

                                        <!-- VARIANTS FOR THIS PRODUCT -->
                                        <tr x-show="openProduct === {{ $item->id }}" x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100">
                                            <td colspan="4" class="p-0">
                                                <div class="px-6 pb-4" style="background: var(--primary-bg);">
                                                    <div class="bg-white rounded-lg shadow-sm overflow-hidden" style="border: 1px solid rgba(139,36,82,0.1);">
                                                        <div class="px-4 py-3 border-b" style="background: var(--primary-bg); border-color: rgba(139,36,82,0.1);">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                                    </svg>
                                                                    <span class="text-sm font-medium text-gray-700">Variant Details</span>
                                                                </div>
                                                                <span class="text-xs text-gray-500">{{ $item->pricing->count() }} variants</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="overflow-x-auto">
                                                            <table class="w-full text-sm">
                                                                <thead class="bg-gray-50">
                                                                    <tr>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Variant</th>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Unit Price</th>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Quantity</th>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Final Price</th>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Discount</th>
                                                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-700">Total Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-100 text-sm">
                                                                    @foreach($item->pricing as $pricing)
                                                                    @php
                                                                        $unitPrice = $pricing->price;
                                                                        $platformQty = $pricing->quantity;
                                                                        $preDiscountTotal = $unitPrice * $platformQty;
                                                                        $discountAmount = 0;
                                                                        if($pricing->discount_type === 'percentage'){
                                                                            $discountAmount = ($preDiscountTotal * $pricing->discount_value) / 100;
                                                                        } elseif($pricing->discount_type === 'fixed'){
                                                                            $discountAmount = $pricing->discount_value;
                                                                        }
                                                                        $finalTotal = $preDiscountTotal - $discountAmount;
                                                                    @endphp
                                                                    <tr class="hover:bg-gray-50/60 transition">
                                                                        <td class="py-3 px-4">
                                                                            @if($pricing->variant && $pricing->variant->variant && $pricing->variant->value)
                                                                                <div class="font-medium text-gray-900">{{ $pricing->variant->variant->name }}</div>
                                                                                <div class="text-xs text-gray-500">{{ $pricing->variant->value->value }}</div>
                                                                            @else
                                                                                <div class="text-xs font-medium text-red-600">Variant Missing</div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="py-3 px-4 font-medium text-gray-800">₹{{ number_format($unitPrice,2) }}</td>
                                                                        <td class="py-3 px-4">{{ $platformQty }}</td>
                                                                        <td class="py-3 px-4 text-gray-700 font-medium">₹{{ number_format($preDiscountTotal,2) }}</td>
                                                                        <td class="py-3 px-4 text-red-600 font-semibold">
                                                                            @if($pricing->discount_value)
                                                                                @if($pricing->discount_type === 'percentage')
                                                                                    {{ $pricing->discount_value }}%
                                                                                @else
                                                                                    ₹{{ number_format($pricing->discount_value, 2) }}
                                                                                @endif
                                                                            @else
                                                                                —
                                                                            @endif
                                                                        </td>
                                                                        <td class="py-3 px-4 text-emerald-700 font-bold">₹{{ number_format($finalTotal,2) }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- EMPTY STATE -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: var(--primary-bg);">
            <svg class="w-10 h-10" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Pushed Yet</h3>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">Start by pushing your first product to external platforms to see them listed here.</p>
        <a href="{{ admin_route('products.push') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            Push Your First Product
        </a>
    </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    [x-cloak] { 
        display: none !important; 
    }
    
    .align-top {
        vertical-align: top;
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endsection