@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('warehouses.index') }}" class="hover:text-[#8B2452] transition-colors">Warehouses</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">{{ $warehouse->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $warehouse->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 20h12M6 4h12M5 8h14M5 12h14M5 16h14" />
                </svg>
                Code: {{ $warehouse->code }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ admin_route('warehouses.edit', $warehouse) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <a href="{{ admin_route('warehouses.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="text-base font-bold text-gray-800">Warehouse Information</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">City</label>
                            <p class="text-base font-semibold text-gray-800">{{ $warehouse->city ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Status</label>
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $warehouse->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Address</label>
                            <p class="text-base font-semibold text-gray-800">{{ $warehouse->address ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Manager Name</label>
                            <p class="text-base font-semibold text-gray-800">{{ $warehouse->manager_name ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Manager Phone</label>
                            <p class="text-base font-semibold text-gray-800">{{ $warehouse->manager_phone ?? '—' }}</p>
                        </div>
                        @if($warehouse->notes)
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Notes</label>
                            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <p class="text-sm text-gray-700">{{ $warehouse->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <h3 class="text-base font-bold text-gray-800">Location Details</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">State</label>
                        <p class="text-base font-semibold text-gray-800">{{ $warehouse->state ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Country</label>
                        <p class="text-base font-semibold text-gray-800">{{ $warehouse->country ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Pincode</label>
                        <p class="text-base font-semibold text-gray-800">{{ $warehouse->pincode ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection