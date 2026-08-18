@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
@endpush

<div class="space-y-6">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-2xl p-6 shadow-lg" style="background: linear-gradient(135deg, var(--primary-light) 0%, #5b1636 100%);">
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -right-2 bottom-0 w-24 h-24 rounded-full bg-white/10"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Coupons Management</h1>
                    <p class="text-sm text-white/70 mt-0.5">Manage all coupon offers and promotions</p>
                </div>
            </div>
            <a href="{{ route('admin.coupons.push') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-white text-[#8B2452] hover:bg-white/90 shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Create Coupon
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-5 border-b border-gray-100 bg-gradient-to-b from-gray-50/70 to-white">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <!-- Search -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   name="search"
                                   id="couponSearch"
                                   class="w-full pl-10 pr-8 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm"
                                   placeholder="Search by name or code..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            @if(request('search'))
                            <span id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
                        <select name="category_id" id="filterCategoryMain" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm auto-submit">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Coupon Type -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Coupon Type</label>
                        <select name="coupon_type" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm auto-submit">
                            <option value="">All Types</option>
                            <option value="NORMAL" {{ request('coupon_type')=='NORMAL'?'selected':'' }}>Coupon</option>
                            <option value="BANK" {{ request('coupon_type')=='BANK'?'selected':'' }}>Bank Offer</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm auto-submit">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                            <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Advanced Filter Button -->
                    <div class="flex items-end">
                        <button type="button" id="openFilterSidebar" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border-2 border-[#8B2452]/20 text-[#8B2452] hover:bg-[#8B2452]/5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Advanced Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Toolbar -->
        <div class="px-5 py-3 border-b border-gray-100 bg-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <p class="text-sm text-gray-500">
                    Showing <span class="font-semibold text-gray-700">{{ $coupons->firstItem() ?? 0 }}</span> to
                    <span class="font-semibold text-gray-700">{{ $coupons->lastItem() ?? 0 }}</span> of
                    <span class="font-semibold text-gray-700">{{ $coupons->total() }}</span> coupons
                </p>
            </div>
            <form method="POST" action="{{ route('admin.coupons.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
                @csrf
                <button type="button" id="openBulkDeleteModal" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-red-600 border border-red-200 hover:bg-red-50 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-gradient-to-b from-gray-50 to-gray-50/50 border-b-2 border-gray-100">
                        <th class="px-4 py-3.5 text-left w-10">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded-md border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                        </th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-16">ID</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Coupon Details</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-28">Code</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-24">Type</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-32">Category</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-28">Platforms</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-24">Status</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-bold text-gray-400 uppercase tracking-widest w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($coupons as $coupon)
                    <tr class="coupon-clickable group hover:bg-[#8B2452]/[0.03] transition-colors cursor-pointer" data-url="{{ route('admin.coupons.show', $coupon) }}">
                        <td class="px-4 py-3.5">
                            <input type="checkbox"
                                   class="row-checkbox w-4 h-4 rounded-md border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20"
                                   name="ids[]"
                                   value="{{ $coupon->id }}"
                                   form="bulkDeleteForm">
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="text-xs font-mono font-medium text-gray-400 bg-gray-50 border border-gray-100 px-2 py-1 rounded-md">#{{ $coupon->id }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div>
                                <span class="text-sm font-bold text-gray-800 group-hover:text-[#8B2452] transition-colors">{{ $coupon->name }}</span>
                                @if($coupon->description)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($coupon->description, 50) }}</p>
                                @endif
                                <p class="text-[11px] text-gray-400 mt-1.5 inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ optional($coupon->starts_at)->format('M d, Y') }} — {{ optional($coupon->expires_at)->format('M d, Y') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="text-xs font-mono font-bold text-[#8B2452] bg-[#8B2452]/[0.07] px-3 py-1.5 rounded-lg tracking-wide">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($coupon->coupon_type == 'BANK')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    Bank Offer
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                    Coupon
                                </span>
                            @endif
                        </td>
                        <!-- Category Column -->
                        <td class="px-4 py-3.5 max-w-[170px]">
                            @if($coupon->product_id)
                                <span class="inline-flex items-center max-w-full px-2.5 py-1 rounded-lg text-[11px] font-bold bg-purple-50 text-purple-600 border border-purple-100" title="{{ $coupon->product->name ?? 'Product' }}">
                                    <svg class="w-3 h-3 shrink-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="truncate">{{ $coupon->product->name ?? 'Product' }}</span>
                                </span>
                            @elseif($coupon->subcategory_id)
                                <span class="inline-flex items-center max-w-full px-2.5 py-1 rounded-lg text-[11px] font-bold bg-sky-50 text-sky-600 border border-sky-100" title="{{ $coupon->subCategory->name ?? 'Subcategory' }}">
                                    <svg class="w-3 h-3 shrink-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    <span class="truncate">{{ $coupon->subCategory->name ?? 'Subcategory' }}</span>
                                </span>
                            @elseif($coupon->category_id)
                                <span class="inline-flex items-center max-w-full px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100" title="{{ $coupon->category->name ?? 'Category' }}">
                                    <svg class="w-3 h-3 shrink-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span class="truncate">{{ $coupon->category->name ?? 'Category' }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center max-w-full px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-50 text-gray-400 border border-gray-100">
                                    <svg class="w-3 h-3 shrink-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    <span class="truncate">All Categories</span>
                                </span>
                            @endif
                            @if($coupon->min_order_amount)
                                <div class="text-[11px] text-gray-400 mt-1 font-medium">
                                    Min: ₹{{ number_format($coupon->min_order_amount, 2) }}
                                </div>
                            @else
                                <div class="text-[11px] text-emerald-500 mt-1 font-medium">
                                    No minimum
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($coupon->platforms as $platform)
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-50 text-gray-500 border border-gray-100">{{ $platform->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($coupon->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-50 text-gray-400 border border-gray-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-1.5"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="p-2 text-gray-400 hover:text-[#8B2452] hover:bg-[#8B2452]/[0.08] transition-all rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" class="inline singleDeleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="openSingleDeleteModal p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all rounded-lg" data-action="{{ route('admin.coupons.destroy', $coupon->id) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-16 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm font-semibold">No coupons found</p>
                            <p class="text-gray-400 text-xs mt-1">Create your first coupon to get started</p>
                            <a href="{{ route('admin.coupons.push') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Coupon
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($coupons->hasPages())
    <div class="mt-4 coupon-pagination flex justify-center md:justify-end">
        {{ $coupons->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center" id="bulkDeleteModal" tabindex="-1">
    <div class="bg-white rounded-2xl shadow-2xl w-96 max-w-md mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h5 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                Confirm Delete
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-bs-dismiss="modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700 mb-1.5" id="deleteModalText">Are you sure you want to delete selected coupons?</p>
            <p class="text-xs text-gray-400">This action cannot be undone.</p>
        </div>
        <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            <button type="button" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-white transition-all" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmBulkDelete" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md" style="background: #dc2626; color: white;">Delete</button>
        </div>
    </div>
</div>

<!-- Advanced Filter Sidebar -->
<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h6 class="font-bold text-gray-800 mb-0 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Advanced Filters
        </h6>
        <button id="closeFilterSidebar" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="p-5">
        <!-- Category Filter -->
        <div class="mb-4">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
            <select name="category_id" id="filterCategory" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr class="my-4 border-gray-100">

        <div class="mb-4">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Filter Field</label>
            <select id="advField" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm">
                <option value="name">Coupon Name</option>
                <option value="code">Coupon Code</option>
                <option value="coupon_type">Coupon Type</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equal</option>
            </select>
        </div>
        <div class="mb-5">
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#8B2452] focus:ring-4 focus:ring-[#8B2452]/10 transition-all text-sm" placeholder="Enter value">
        </div>
        <button id="applyAdvancedFilter" class="w-full py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md mb-2" style="background: var(--primary-light); color: white;">Apply Filter</button>
        <button id="clearAdvancedFilter" class="w-full py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">Clear Filter</button>
    </div>
</div>

<style>
.filter-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    background: #ffffff;
    transform: translateX(100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    box-shadow: -8px 0 30px rgba(0,0,0,0.12);
}
.filter-sidebar.open {
    transform: translateX(0);
}
.filter-sidebar-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to bottom, #fafafa, #ffffff);
}
.modal.fade {
    display: none;
}
.modal.fade.show {
    display: flex !important;
}

/* Compact pagination look */
.coupon-pagination nav > div:first-child {
    display: none !important; /* hide default mobile prev/next text block */
}
.coupon-pagination nav > .flex.justify-between.flex-1.sm\:hidden {
    display: none !important;
}
.coupon-pagination p.text-sm.text-gray-700 {
    display: none !important; /* hide duplicate "Showing X to Y" text, already shown above table */
}
.coupon-pagination span[aria-current] {
    background: var(--primary-light) !important;
    border-color: var(--primary-light) !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(139,36,82,0.35);
}
.coupon-pagination nav a,
.coupon-pagination nav span[aria-current] {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 8px !important;
    margin: 0 3px !important;
    border-radius: 10px !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    line-height: 1 !important;
    border: 1px solid #e5e7eb !important;
    color: #4b5563 !important;
    background: #fff !important;
    box-shadow: none !important;
    transition: all 0.15s ease;
}
.coupon-pagination nav a:hover {
    background: #faf1f5 !important;
    border-color: var(--primary-light) !important;
    color: var(--primary-light) !important;
}
.coupon-pagination nav > div.hidden.sm\:flex {
    display: flex !important;
    justify-content: center !important;
    width: 100%;
}
.coupon-pagination nav svg {
    width: 14px;
    height: 14px;
}
.coupon-pagination nav [disabled],
.coupon-pagination nav span:not([aria-current]) {
    color: #cbd5e1 !important;
    border-color: #f1f5f9 !important;
}
</style>

@endsection
