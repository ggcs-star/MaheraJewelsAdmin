@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('dashboard') }}" class="hover:text-[#8B2452] transition-colors">Dashboard</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ admin_route('products.index') }}" class="hover:text-[#8B2452] transition-colors">Inventory</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 font-medium">{{ $product->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Product Details</h1>
            <p class="text-sm text-gray-500 mt-0.5">Product ID: #{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }} | SKU: {{ $product->sku }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ admin_route('products.edit', $product) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Product
            </a>
            <a href="{{ admin_route('products.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-emerald-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 capitalize">{{ $product->status }}</p>
            <p class="text-xs text-gray-500 mt-1">Product status</p>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-indigo-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Visibility</span>
            </div>
            <p class="text-2xl font-bold text-gray-800 capitalize">{{ $product->visibility }}</p>
            <p class="text-xs text-gray-500 mt-1">Visibility status</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-blue-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Variants</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $product->variants->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Product variants</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-amber-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Inventory Value</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">₹{{ number_format($product->variants->sum('total_price'), 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Based on variants</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Product Overview
                </h3>
            </div>
            <div class="p-5 text-center">
                @php
                    $mainImage = null;
                    if (!empty($product->image_url) && is_string($product->image_url)) {
                        $mainImage = $product->image_url;
                    } elseif (!empty($product->gallery_images) && is_array($product->gallery_images) && isset($product->gallery_images[0])) {
                        $mainImage = $product->gallery_images[0];
                    }
                @endphp

                @if($mainImage)
                    <div class="mb-4 rounded-lg border border-gray-200 overflow-hidden bg-gray-100">
                        <img id="mainProductImage" src="{{ \App\Helpers\S3Helper::url(ltrim($mainImage,'/')) }}" class="w-full h-48 object-cover" alt="{{ $product->name }}">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl font-bold shadow-sm" style="background: #8B2452; color: white;">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>
                @endif

                @if(!empty($product->gallery_images) && is_array($product->gallery_images))
                    <div class="flex gap-2 flex-wrap justify-center mb-4">
                        @foreach($product->gallery_images as $img)
                            @if(is_string($img) && str_contains($img, '/'))
                                <img src="{{ \App\Helpers\S3Helper::url(ltrim($img,'/')) }}" class="w-14 h-14 rounded border border-gray-200 object-cover cursor-pointer hover:border-[#8B2452] transition-all" onclick="changeMainImage(this.src)" alt="gallery">
                            @endif
                        @endforeach
                    </div>
                @endif

                <h4 class="text-xl font-bold text-gray-800">{{ $product->name }}</h4>
                <p class="text-sm text-gray-500 mb-3">SKU: {{ $product->sku }}</p>

                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($product->status) }}
                </span>

                <div class="border-t border-gray-100 mt-5 pt-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Category</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $product->category?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Supplier</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $product->supplier?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 8.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v8.25A2.25 2.25 0 006 16.5h2.25m8.25-8.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-7.5A2.25 2.25 0 018.25 18v-1.5m8.25-8.25h-6a2.25 2.25 0 00-2.25 2.25v6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Base Selling Price</p>
                            <p class="text-sm font-semibold text-gray-800">₹{{ number_format($product->base_selling_price, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Basic Information
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Product Name</p>
                            <p class="text-base font-semibold text-gray-800">{{ $product->name }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Slug</p>
                            <p class="text-base font-semibold text-gray-800">{{ $product->slug }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">SKU</p>
                            <p class="text-base font-semibold text-gray-800">{{ $product->sku }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Procurement Details
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Warehouse</p>
                            <p class="text-base font-semibold text-gray-800">{{ $product->warehouse ? $product->warehouse->city . ' — ' . $product->warehouse->name : 'N/A' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Expected Delivery</p>
                            <p class="text-base font-semibold text-gray-800">{{ $product->expected_delivery_date ? \Carbon\Carbon::parse($product->expected_delivery_date)->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Payment Terms</p>
                            <p class="text-base font-semibold text-gray-800 capitalize">{{ $product->payment_terms ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($product->description || $product->short_description)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Description
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    @if($product->short_description)
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Short Description</p>
                            <p class="text-sm text-gray-700">{{ $product->short_description }}</p>
                        </div>
                    @endif
                    @if($product->description)
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Full Description</p>
                            <p class="text-sm text-gray-700">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pricing Information
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="p-3 rounded-lg border-l-4 border-l-amber-500 border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Cost Price</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($product->cost_price, 2) }}</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-emerald-500 border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Base Selling Price</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($product->base_selling_price, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            Settings & SEO
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <span class="text-sm font-medium text-gray-700">Featured</span>
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $product->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $product->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <span class="text-sm font-medium text-gray-700">Top Selling</span>
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $product->is_top_selling ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $product->is_top_selling ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        @if($product->meta_title)
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs text-gray-400 mb-1">Meta Title</p>
                            <p class="text-sm font-medium text-gray-700">{{ $product->meta_title }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Product Variants
            </h3>
            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">{{ $product->variants->count() }} Variant(s)</span>
        </div>
        <div class="overflow-x-auto">
            @if($product->variants->count() > 0)
            <table class="w-full min-w-[1000px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU Suffix</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Image</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cost Price</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Selling Price</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Value</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($product->variants as $index => $variant)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-center text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $variant->variant->name }}</span></td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $variant->value->value }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $variant->sku_suffix ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($variant->image_url)
                                <img src="{{ \App\Helpers\S3Helper::url($variant->image_url) }}" class="w-10 h-10 rounded border border-gray-200 object-cover mx-auto">
                            @else
                                <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center mx-auto"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $variant->quantity }}</span></td>
                        <td class="px-4 py-3 text-right text-sm text-gray-600">₹{{ number_format($variant->purchase_price, 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm text-gray-600">₹{{ number_format($variant->selling_price, 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-emerald-600">₹{{ number_format($variant->total_price, 2) }}</td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $variant->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($variant->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-100">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Totals:</td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $product->variants->sum('quantity') }}</span></td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-700">₹{{ number_format($product->variants->sum('purchase_price'), 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-700">₹{{ number_format($product->variants->sum('selling_price'), 2) }}</td>
                        <td class="px-4 py-3 text-right text-base font-bold text-emerald-600">₹{{ number_format($product->variants->sum('total_price'), 2) }}</td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="text-center py-10">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <p class="text-sm text-gray-400">No variants added for this product</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function changeMainImage(src) {
    const mainImg = document.getElementById('mainProductImage');
    if (mainImg) {
        mainImg.src = src;
    }
}
</script>
@endsection