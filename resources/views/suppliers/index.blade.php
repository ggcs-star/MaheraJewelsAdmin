@extends('layouts.admin')

@section('content')
@push('scripts')
    <script src="{{ asset('assets/js/admin/suppliers.js') }}"></script>
@endpush

<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Suppliers</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your supplier network and partnerships</p>
        </div>
        <a href="{{ admin_route('suppliers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Supplier
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="supplierFilterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                name="search"
                                id="supplierSearch"
                                class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base"
                                placeholder="Name, company or phone..."
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                            <span
                                id="clearSupplierSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer text-sm"
                                style="display:none;"
                            >
                                ✕
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Supplier Type</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white">
                            <option value="">All Types</option>
                            <option value="manufacturer" {{ request('type') == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                            <option value="distributor" {{ request('type') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button
                            type="button"
                            id="openSupplierFilterSidebar"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Advanced Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

   <form method="POST"
      action="{{ route('admin.suppliers.bulk-delete') }}"
      id="supplierBulkDeleteForm">
    @csrf
</form>
            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAllSuppliers" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                    <span class="text-sm font-medium text-gray-600">Select All</span>
                </div>
                <button type="button" id="bulkDeleteBtn" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left w-10">
                                <span class="sr-only">Select</span>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Supplier Name</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Commission</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.suppliers.details', $supplier->id) }}'">
                                <td class="px-4 py-3" onclick="event.stopPropagation()">
                                    <input type="checkbox"
                                           class="supplier-row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20"
                                           name="ids[]"
                                           value="{{ $supplier->id }}"
                                           form="supplierBulkDeleteForm">
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-500">
                                    {{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        {{ ucfirst($supplier->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-base font-bold text-gray-800">{{ $supplier->name }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $supplier->company_name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $supplier->phone }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($supplier->commission_type === 'percentage')
                                        <span class="text-sm font-semibold text-[#8B2452]">
                                            {{ $supplier->commission_value }}%
                                        </span>
                                    @else
                                        <span class="text-sm font-semibold text-emerald-600">
                                            ₹{{ number_format($supplier->commission_value, 2) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ admin_route('suppliers.edit', $supplier) }}" 
                                           class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ admin_route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
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
                                <td colspan="9" class="px-4 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">No suppliers found matching your filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suppliers->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                    {{ $suppliers->withQueryString()->links() }}
                </div>
            @endif
    </div>
</div>

<div id="supplierFilterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h5 class="mb-0 font-bold text-gray-800">Advanced Filter</h5>
        <button type="button" id="closeSupplierFilterSidebar" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
    </div>
    <div class="filter-sidebar-body">
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Field</label>
            <select id="advField" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="name">Name</option>
                <option value="company_name">Company</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="type">Type</option>
                <option value="status">Status</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equals</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Enter value...">
        </div>
        <button type="button" id="applySupplierAdvancedFilter" class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">Apply Filter</button>
        <button type="button" id="clearSupplierAdvancedFilter" class="w-full mt-2 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">Clear Filter</button>
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
    z-index: 1000;
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
.filter-sidebar-body {
    padding: 24px;
}
</style>
@endsection