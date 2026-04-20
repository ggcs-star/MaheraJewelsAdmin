@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ admin_route('dashboard') }}" class="hover:text-[#8B2452] transition-colors">Dashboard</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ admin_route('suppliers.index') }}" class="hover:text-[#8B2452] transition-colors">Suppliers</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">{{ $supplier->name }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-800">Supplier Profile</h1>
            <p class="text-gray-500 text-xs mt-0.5">Supplier ID: #{{ str_pad($supplier->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ admin_route('suppliers.edit', $supplier) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Supplier
            </a>
            <a href="{{ admin_route('suppliers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg border border-gray-100 p-3 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Supplier Type</span>
            </div>
            <p class="text-lg font-bold text-gray-800 capitalize">{{ $supplier->type }}</p>
            <p class="text-[10px] text-gray-400">Manufacturer/Supplier</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-100 p-3 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8.25h.01M9 12h.01M9 15.75h.01M15 8.25h.01M15 12h.01M15 15.75h.01M4.5 3.75h15a2.25 2.25 0 012.25 2.25v12a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-12a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                </div>
                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Commission</span>
            </div>
            <p class="text-lg font-bold text-gray-800">
                @if ($supplier->commission_type === 'percentage')
                    {{ $supplier->commission_value }}%
                @else
                    ₹{{ number_format($supplier->commission_value, 2) }}
                @endif
            </p>
            <p class="text-[10px] text-gray-400 capitalize">{{ $supplier->commission_type }} based</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-100 p-3 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Payment Terms</span>
            </div>
            <p class="text-lg font-bold text-gray-800">{{ $supplier->payment_terms ?? '—' }}</p>
            <p class="text-[10px] text-gray-400">Payment duration</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-100 p-3 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg {{ $supplier->status === 'active' ? 'bg-emerald-100' : 'bg-gray-100' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 {{ $supplier->status === 'active' ? 'text-emerald-700' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Status</span>
            </div>
            <p class="text-lg font-bold text-gray-800 capitalize">{{ $supplier->status }}</p>
            <p class="text-[10px] text-gray-400">Account status</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Supplier Details
                </h3>
            </div>
            <div class="p-4 text-center">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold shadow-sm" style="background: #8B2452; color: white;">
                    {{ strtoupper(substr($supplier->name, 0, 1)) }}
                </div>
                <h4 class="text-base font-bold text-gray-800">{{ $supplier->name }}</h4>
                <p class="text-xs text-gray-500 mb-3">{{ $supplier->company_name ?? 'Individual Supplier' }}</p>
                
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($supplier->status) }}
                </span>

                <div class="border-t border-gray-100 mt-4 pt-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.347l-.664.719a3.75 3.75 0 01-2.879 1.143 3.75 3.75 0 01-2.879-1.143l-.664-.719c-.271-.292-.733-.457-1.173-.347l-4.423 1.106c-.501.125-.852.575-.852 1.091V19.5a2.25 2.25 0 002.25 2.25h2.25z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Phone</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Email</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->email ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                        </svg>
                        Address Information
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] text-gray-400 mb-1">Address</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->address ?? '—' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] text-gray-400 mb-1">City</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->city ?? '—' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] text-gray-400 mb-1">State</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->state ?? '—' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] text-gray-400 mb-1">Country</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->country ?? '—' }}</p>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-[10px] text-gray-400 mb-1">Pincode</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->pincode ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Tax Information
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 mb-1">GST Number</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->gst_number ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 mb-1">PAN Number</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->pan_number ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659a1.5 1.5 0 001.5.164l3.191-.957a1.5 1.5 0 00.932-1.34V9.818a1.5 1.5 0 00-1.052-1.433l-3.191-.957a1.5 1.5 0 00-1.5.164L9 8.182m0 0L6 6" />
                            </svg>
                            Agreement Details
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 mb-1">Commission Type</p>
                            <p class="text-sm font-medium text-gray-800 capitalize">{{ $supplier->commission_type }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 mb-1">Commission Value</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if ($supplier->commission_type === 'percentage')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">{{ $supplier->commission_value }}%</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">₹{{ number_format($supplier->commission_value, 2) }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 mb-1">Payment Terms</p>
                            <p class="text-sm font-medium text-gray-800">{{ $supplier->payment_terms ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 8.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v8.25A2.25 2.25 0 006 16.5h2.25m8.25-8.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-7.5A2.25 2.25 0 018.25 18v-1.5m8.25-8.25h-6a2.25 2.25 0 00-2.25 2.25v6" />
                        </svg>
                        Internal Notes
                    </h3>
                </div>
                <div class="p-4">
                    <div class="p-4 rounded-lg bg-gray-50/50 border border-gray-100">
                        @if($supplier->notes)
                            <p class="text-sm text-gray-600">{{ $supplier->notes }}</p>
                        @else
                            <p class="text-sm text-gray-400 text-center">No notes added for this supplier</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection