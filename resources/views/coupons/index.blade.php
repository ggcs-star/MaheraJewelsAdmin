@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
@endpush

<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Coupons Management</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage all coupon offers and promotions</p>
        </div>
        <a href="{{ route('admin.coupons.push') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Create Coupon
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   name="search"
                                   id="couponSearch"
                                   class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base"
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

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Coupon Type</label>
                        <select name="coupon_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white auto-submit">
                            <option value="">All Types</option>
                            <option value="NORMAL" {{ request('coupon_type')=='NORMAL'?'selected':'' }}>Coupon</option>
                            <option value="BANK" {{ request('coupon_type')=='BANK'?'selected':'' }}>Bank Offer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white auto-submit">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                            <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="button" id="openFilterSidebar" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Advanced Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Showing {{ $coupons->firstItem() ?? 0 }} to {{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} coupons</p>
            </div>
            <form method="POST" action="{{ route('admin.coupons.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
                @csrf
                <button type="button" id="openBulkDeleteModal" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Coupon Details</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Platforms</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($coupons as $coupon)
                    <tr class="coupon-clickable hover:bg-gray-50 transition-colors cursor-pointer" data-url="{{ route('admin.coupons.show', $coupon) }}">
                        <td class="px-4 py-3">
                            <input type="checkbox"
                                   class="row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20"
                                   name="ids[]"
                                   value="{{ $coupon->id }}"
                                   form="bulkDeleteForm">
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $coupon->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <span class="text-base font-bold text-gray-800">{{ $coupon->name }}</span>
                                @if($coupon->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($coupon->description, 50) }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ optional($coupon->starts_at)->format('M d, Y') }} - {{ optional($coupon->expires_at)->format('M d, Y') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-mono font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-lg">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($coupon->coupon_type == 'BANK')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    Bank Offer
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                    Coupon
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($coupon->platforms as $platform)
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">{{ $platform->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($coupon->is_active)
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" class="inline singleDeleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="openSingleDeleteModal p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" data-action="{{ route('admin.coupons.destroy', $coupon->id) }}">
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
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No coupons found</p>
                            <p class="text-gray-400 text-xs mt-1">Create your first coupon to get started</p>
                            <a href="{{ route('admin.coupons.push') }}" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">
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
    <div class="mt-4">
        {{ $coupons->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<div class="modal fade fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" id="bulkDeleteModal" tabindex="-1">
    <div class="bg-white rounded-xl shadow-xl w-96 max-w-md mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h5 class="text-base font-semibold text-red-600">Confirm Delete</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-bs-dismiss="modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-5 text-center">
            <svg class="w-12 h-12 mx-auto text-amber-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-sm text-gray-700 mb-2" id="deleteModalText">Are you sure you want to delete selected coupons?</p>
            <p class="text-xs text-gray-400">This action cannot be undone.</p>
        </div>
        <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            <button type="button" class="px-4 py-1.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmBulkDelete" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all shadow-sm" style="background: #dc2626; color: white;">Delete</button>
        </div>
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h6 class="font-bold text-gray-800 mb-0">Advanced Filters</h6>
        <button id="closeFilterSidebar" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="p-4">
        <div class="mb-3">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Filter Field</label>
            <select id="advField" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="name">Coupon Name</option>
                <option value="code">Coupon Code</option>
                <option value="coupon_type">Coupon Type</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equal</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Enter value">
        </div>
        <button id="applyAdvancedFilter" class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm mb-2" style="background: var(--primary-light); color: white;">Apply Filter</button>
        <button id="clearAdvancedFilter" class="w-full py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">Clear Filter</button>
    </div>
</div>

<style>
.filter-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    background: white;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1050;
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
}
.filter-sidebar.open {
    transform: translateX(0);
}
.filter-sidebar-header {
    padding: 18px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal.fade {
    display: none;
}
.modal.fade.show {
    display: flex !important;
}
</style>
@endsection