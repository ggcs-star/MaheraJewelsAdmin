@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ admin_route('dashboard') }}" class="hover:text-[#8B2452] transition-colors">Dashboard</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ admin_route('organizations.index') }}" class="hover:text-[#8B2452] transition-colors">Organizations</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">{{ $organization->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Organization Details</h1>
            <p class="text-sm text-gray-500 mt-0.5">Organization ID: {{ $organization->id }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ admin_route('organizations.edit', $organization) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Organization
            </a>
            <a href="{{ admin_route('organizations.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-emerald-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Status</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $organization->is_active ? 'Active' : 'Inactive' }}</p>
            <p class="text-xs text-emerald-600 mt-1">Organization status</p>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-indigo-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Email</span>
            </div>
            <p class="text-base font-semibold text-gray-800 break-all">{{ $organization->email ?? '—' }}</p>
            <p class="text-xs text-indigo-600 mt-1">Official email</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-blue-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Mobile</span>
            </div>
            <p class="text-base font-semibold text-gray-800">{{ $organization->mobile ?? '—' }}</p>
            <p class="text-xs text-blue-600 mt-1">Contact number</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-amber-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Website</span>
            </div>
            <p class="text-base font-semibold text-gray-800 break-all">{{ $organization->website ?? '—' }}</p>
            <p class="text-xs text-amber-600 mt-1">Official website</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT COLUMN - Organization Overview --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-base font-bold text-gray-800">Organization Overview</h3>
                </div>
            </div>
            <div class="p-5 text-center">
                @if($organization->logo_url)
                    <div class="mb-4 rounded-lg border border-gray-200 overflow-hidden bg-gray-100">
                        <img src="{{ $organization->logo_url }}" class="w-full h-36 object-cover">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl font-bold shadow-sm" style="background: var(--primary-light); color: white;">
                        {{ strtoupper(substr($organization->name, 0, 1)) }}
                    </div>
                @endif

                <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $organization->name }}</h4>
                <p class="text-sm text-gray-500 mb-3">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    {{ $organization->address ?? 'No address added' }}
                </p>

                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $organization->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $organization->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        {{-- RIGHT COLUMN - Basic Information --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-base font-bold text-gray-800">Basic Information</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->email ?? '—' }}</p>
                        </div>
                        <!-- Mobile -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Mobile</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->mobile ?? '—' }}</p>
                        </div>
                        <!-- Website -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Website</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->website ?? '—' }}</p>
                        </div>
                        <!-- Address -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Address</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->address ?? '—' }}</p>
                        </div>
                        <!-- Business Hours -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Business Hours</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->business_hours ?? '—' }}</p>
                        </div>

                        <!-- ✅ INVOICE NAME -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Invoice Name</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->invoice_name ?? '—' }}</p>
                        </div>

                        <!-- ✅ INVOICE EMAIL -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Invoice Email</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $organization->invoice_email ?? '—' }}</p>
                        </div>

                        <!-- ✅ INVOICE LOGO -->
                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Invoice Logo</p>
                            @if($organization->invoice_logo)
                                <div class="mt-1">
                                    <img src="{{ \App\Helpers\S3Helper::url($organization->invoice_logo) }}" alt="Invoice Logo" class="w-16 h-16 rounded-lg border border-gray-200 object-cover">
                                </div>
                            @else
                                <p class="text-sm font-semibold text-gray-800">—</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection