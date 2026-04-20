@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('delivery-settings.index') }}" class="hover:text-[#8B2452] transition-colors">Delivery Settings</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">Delivery Setting Details</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Delivery Setting Details</h1>
            <p class="text-sm text-gray-500 mt-0.5">View delivery and platform fee details</p>
        </div>
        <a href="{{ admin_route('delivery-settings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="p-4 rounded-lg border border-gray-100 bg-gradient-to-br from-indigo-50 to-indigo-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 20h12M6 4h12M5 8h14M5 12h14M5 16h14" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Delivery Fee</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">₹{{ number_format($deliverySetting->delivery_fee, 2) }}</p>
                    <p class="text-xs text-indigo-600 mt-1">Delivery charges per order</p>
                </div>

                <div class="p-4 rounded-lg border border-gray-100 bg-gradient-to-br from-blue-50 to-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 20h12M6 4h12M5 8h14M5 12h14M5 16h14" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Platform Fee</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">₹{{ number_format($deliverySetting->platform_fee, 2) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Platform service charges</p>
                </div>

                <div class="p-4 rounded-lg border border-gray-100 bg-gradient-to-br from-emerald-50 to-emerald-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5 0h.01M14 14h.01M5 7h14v10H5z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Tax</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $deliverySetting->tax_percent }}%</p>
                    <p class="text-xs text-emerald-600 mt-1">GST / Tax percentage</p>
                </div>

                <div class="p-4 rounded-lg border border-gray-100 bg-gradient-to-br from-amber-50 to-amber-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Free Delivery Above</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">
                        @if($deliverySetting->free_delivery_above > 0)
                            ₹{{ number_format($deliverySetting->free_delivery_above, 2) }}
                        @else
                            <span class="text-base text-gray-400">Not set</span>
                        @endif
                    </p>
                    <p class="text-xs text-amber-600 mt-1">Orders above this amount get free delivery</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection