@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-5 coupon-details-page">

    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ admin_route('dashboard') }}" class="hover:text-primary transition">Dashboard</a>
                <span>/</span>
                <a href="{{ admin_route('coupons.index') }}" class="hover:text-primary transition">Coupons</a>
                <span>/</span>
                <span class="text-primary font-medium">{{ $coupon->code }}</span>
            </nav>

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Coupon Details</h2>
            <div class="text-sm text-gray-500">
                Code: <span class="font-semibold text-primary">{{ $coupon->code }}</span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ admin_route('coupons.edit', $coupon) }}" class="px-4 py-2 rounded-lg transition font-medium" style="background: #440C2C; color: white;">
                Edit Coupon
            </a>
            <a href="{{ admin_route('coupons.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-medium">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">Status</div>
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $coupon->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">Coupon Type</div>
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $coupon->coupon_type === 'BANK' ? 'bg-sky-50 text-sky-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $coupon->coupon_type }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">Discount Value</div>
                    <div class="text-xl font-bold text-amber-600">
                        {{ $coupon->discount_type === 'PERCENT'
                            ? $coupon->value . '%'
                            : '₹' . number_format($coupon->value) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 mb-1">Usage Statistics</div>
                    <div class="text-xl font-bold text-emerald-600">{{ $coupon->used_count }} / {{ $coupon->usage_limit }}</div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-emerald-600 rounded-full h-1.5" style="width: {{ ($coupon->used_count / max(1,$coupon->usage_limit)) * 100 }}%"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">{{ number_format(($coupon->used_count / max(1,$coupon->usage_limit)) * 100, 1) }}% utilized</div>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <h5 class="font-semibold text-gray-800">Coupon Overview</h5>
                </div>

                <div class="p-5 text-center">
                    <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <h4 class="font-bold text-gray-800 mb-2">{{ $coupon->name }}</h4>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary mb-3">
                        {{ $coupon->code }}
                    </span>

                    <div class="bg-gray-50 rounded-lg p-3 mt-3 mb-4">
                        <p class="text-sm text-gray-500 mb-0">
                            {{ $coupon->description ?? 'No description provided.' }}
                        </p>
                    </div>

                    @if($coupon->coupon_type === 'BANK')
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <div class="mb-2">
                                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700">
                                    {{ $coupon->bank?->name ?? 'No Bank' }}
                                </span>
                            </div>
                            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                Card Type: {{ ucfirst($coupon->card_type) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/30">
                    <small class="text-xs text-gray-400">
                        Created: {{ $coupon->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="space-y-5">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <h5 class="font-semibold text-gray-800">Rules & Limits</h5>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Minimum Order</div>
                                <div class="font-semibold text-gray-800">₹{{ number_format($coupon->min_order_amount) }}</div>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Max Discount</div>
                                <div class="font-semibold text-gray-800">{{ $coupon->max_discount ? '₹'.number_format($coupon->max_discount) : 'No limit' }}</div>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Platforms</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($coupon->platforms as $platform)
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs bg-white text-gray-600 border border-gray-200">
                                            {{ $platform->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <h5 class="font-semibold text-gray-800">Validity Period</h5>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Start Date</div>
                                <div class="font-semibold text-gray-800">{{ $coupon->starts_at->format('d M Y') }}</div>
                                <small class="text-xs text-gray-400">{{ $coupon->starts_at->format('h:i A') }}</small>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Expiry Date</div>
                                <div class="font-semibold text-gray-800">{{ $coupon->expires_at->format('d M Y') }}</div>
                                <small class="text-xs text-gray-400">{{ $coupon->expires_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        
                        @php
                            $now = now();
                            $isExpired = $now->greaterThan($coupon->expires_at);
                            $isUpcoming = $now->lessThan($coupon->starts_at);
                            $isActive = !$isExpired && !$isUpcoming;
                        @endphp
                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $isActive ? 'bg-emerald-50 text-emerald-700' : ($isExpired ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                                {{ $isActive ? 'Currently Active' : ($isExpired ? 'Expired' : 'Upcoming') }}
                            </span>
                            <div class="text-xs text-gray-400">
                                {{ $isActive 
                                    ? 'Expires in ' . $coupon->expires_at->diffForHumans()
                                    : ($isExpired 
                                        ? 'Expired ' . $coupon->expires_at->diffForHumans()
                                        : 'Starts ' . $coupon->starts_at->diffForHumans())
                                }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <h5 class="font-semibold text-gray-800">Discount Information</h5>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Discount Type</div>
                                <div class="font-semibold text-gray-800">{{ $coupon->discount_type === 'PERCENT' ? 'Percentage Discount' : 'Fixed Amount' }}</div>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">Discount Value</div>
                                <div class="font-semibold text-primary">{{ $coupon->discount_type === 'PERCENT' ? $coupon->value . '% OFF' : '₹' . number_format($coupon->value) . ' OFF' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection