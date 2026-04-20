@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Customers</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage customers</p>
        </div>
        <a href="{{ admin_route('customers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Customer
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text"
                               id="customerSearch"
                               class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                               placeholder="Search name / email / mobile..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
                    <select id="statusFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                        <option value="">All</option>
                        <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                        <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="openAdvancedFilter" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all" style="background: var(--primary-light); color: white;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Advanced Filter
                    </button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ admin_route('customers.bulk-delete') }}" id="bulkDeleteForm">
            @csrf

            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                    <span class="text-sm font-medium text-gray-600">Select All</span>
                </div>
                <button type="button" id="openBulkDelete" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
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
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($customers as $customer)
                        <tr class="clickable-row hover:bg-gray-50 transition-colors cursor-pointer" data-url="{{ admin_route('customers.show',$customer) }}">
                            <td class="px-4 py-3" onclick="event.stopPropagation()">
                                <input type="checkbox" name="ids[]" value="{{ $customer->id }}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20">
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-bold text-gray-800">{{ $customer->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $customer->city }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600">{{ $customer->email }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $customer->mobile }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $customer->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ admin_route('customers.edit',$customer) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" class="openSingleDelete p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" data-url="{{ admin_route('customers.destroy',$customer) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $customers->links() }}
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title text-red-600 font-bold">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-2">
                <svg class="w-12 h-12 mx-auto text-amber-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-gray-700 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm" id="confirmDelete" style="background: #dc2626; color: white;">Delete</button>
            </div>
        </div>
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-header px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            <strong class="text-gray-800 font-bold">Advanced Filter</strong>
        </div>
        <button type="button" id="closeAdvancedFilter" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="p-5">
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Filter Field</label>
            <select id="advField" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="mobile">Mobile</option>
                <option value="city">City</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equal</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
            </select>
        </div>
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Enter value..." value="{{ request('adv_value') }}">
        </div>
        <div class="grid gap-2">
            <button type="button" id="applyAdvancedFilter" class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">
                Apply Filter
            </button>
            <button type="button" id="clearAdvancedFilter" class="w-full py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                Clear Filter
            </button>
        </div>
    </div>
</div>

<div id="filterOverlay" class="filter-overlay"></div>

<style>
.filter-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 380px;
    height: 100vh;
    background: white;
    box-shadow: -4px 0 20px rgba(0,0,0,0.1);
    z-index: 1060;
    transition: right 0.3s ease;
    overflow-y: auto;
    display: none;
}
.filter-sidebar.show {
    right: 0;
    display: block;
}
.filter-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1059;
    display: none;
}
.filter-overlay.show {
    display: block;
}
.modal.fade {
    display: none;
}
.modal.fade.show {
    display: flex !important;
}
</style>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/customer.js') }}"></script>
@endpush