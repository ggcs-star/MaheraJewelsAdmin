@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Organizations Management</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage all organizations and their details</p>
        </div>
        <a href="{{ admin_route('organizations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Organization
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search Organizations</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" id="orgSearch" class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Search by name, email or mobile..." value="{{ request('search') }}" autocomplete="off">
                            @if(request('search'))
                            <button type="button" id="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white auto-submit">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="openAdvancedFilter" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
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
                <p class="text-sm font-medium text-gray-600">Showing {{ $organizations->firstItem() ?? 0 }} to {{ $organizations->lastItem() ?? 0 }} of {{ $organizations->total() }} organizations</p>
            </div>
            <form method="POST" action="{{ admin_route('organizations.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
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
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Logo</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Organization Details</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Contact Info</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($organizations as $organization)
                        <tr class="clickable-row hover:bg-gray-50 transition-colors cursor-pointer" data-url="{{ admin_route('organizations.show', $organization) }}">
                            <td class="px-4 py-3" onclick="event.stopPropagation()">
                                <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20" name="ids[]" value="{{ $organization->id }}" form="bulkDeleteForm">
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-500">{{ $organization->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="logo-container cursor-zoom-in" onclick="event.stopPropagation(); showLogoModal('{{ $organization->logo_url }}', '{{ $organization->name }}');">
                                    @if($organization->logo_url)
                                        <img src="{{ $organization->logo_url }}" alt="{{ $organization->name }} Logo" class="w-10 h-10 rounded-full border border-gray-200 object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--primary-bg);">
                                            <svg class="w-5 h-5" style="color: var(--primary-light);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <span class="text-base font-bold text-gray-800 d-block mb-1">{{ $organization->name }}</span>
                                    @if($organization->city)
                                        <small class="text-xs text-gray-500">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $organization->city }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="space-y-1">
                                    @if($organization->email)
                                        <small class="text-xs text-gray-600 block">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $organization->email }}
                                        </small>
                                    @endif
                                    @if($organization->mobile)
                                        <small class="text-xs text-gray-600 block">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $organization->mobile }}
                                        </small>
                                    @endif
                                    @if($organization->business_hours)
                                        <small class="text-xs text-gray-600 block">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $organization->business_hours }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $organization->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ admin_route('organizations.edit', $organization) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50" onclick="event.stopPropagation()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ admin_route('organizations.destroy', $organization) }}" method="POST" onsubmit="return confirm('Delete this organization?');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50" onclick="event.stopPropagation()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($organizations->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                <div class="flex justify-between items-center flex-wrap gap-2">
                    <div class="text-sm font-medium text-gray-500">Showing {{ $organizations->firstItem() }} to {{ $organizations->lastItem() }} of {{ $organizations->total() }} entries</div>
                    <div>{{ $organizations->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        @endif
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
                <p class="text-sm text-gray-700 mb-2" id="deleteModalText"></p>
                <p class="text-xs text-gray-400">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm" id="confirmDelete" style="background: #dc2626; color: white;">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img id="modalLogoImage" src="" class="max-w-full max-h-[80vh] rounded-lg" style="margin: 0 auto;">
            </div>
        </div>
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h5 class="mb-0 font-bold text-gray-800">Advanced Filters</h5>
        <button type="button" id="closeAdvancedFilter" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
    </div>
    <div class="p-5">
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Filter Field</label>
            <select id="advField" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="name" {{ request('adv_field') == 'name' ? 'selected' : '' }}>Organization Name</option>
                <option value="email" {{ request('adv_field') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="mobile" {{ request('adv_field') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="city" {{ request('adv_field') == 'city' ? 'selected' : '' }}>City</option>
                <option value="address" {{ request('adv_field') == 'address' ? 'selected' : '' }}>Address</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condition</label>
            <select id="advCondition" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                <option value="like" {{ request('adv_condition') == 'like' ? 'selected' : '' }}>Contains</option>
                <option value="=" {{ request('adv_condition') == '=' ? 'selected' : '' }}>Equals</option>
                <option value="!=" {{ request('adv_condition') == '!=' ? 'selected' : '' }}>Not Equal</option>
                <option value="starts_with" {{ request('adv_condition') == 'starts_with' ? 'selected' : '' }}>Starts With</option>
                <option value="ends_with" {{ request('adv_condition') == 'ends_with' ? 'selected' : '' }}>Ends With</option>
            </select>
        </div>
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Value</label>
            <input type="text" id="advValue" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm" placeholder="Enter value" value="{{ request('adv_value') }}">
        </div>
        <div class="grid gap-2">
            <button type="button" id="applyAdvancedFilter" class="w-full py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">Apply Filter</button>
            <button type="button" id="clearAdvancedFilter" class="w-full py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">Clear Filter</button>
        </div>
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
.filter-sidebar.show {
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

@push('scripts')
<script>
function showLogoModal(imageUrl, name) {
    if (!imageUrl) return;
    const img = document.getElementById('modalLogoImage');
    img.src = imageUrl;
    img.alt = name;
    const modal = new bootstrap.Modal(document.getElementById('logoModal'));
    modal.show();
}

document.getElementById('closeAdvancedFilter')?.addEventListener('click', function () {
    document.getElementById('filterSidebar').classList.remove('show');
});

document.getElementById('openAdvancedFilter')?.addEventListener('click', function () {
    document.getElementById('filterSidebar').classList.add('show');
});
</script>
<script src="{{ asset('assets/js/admin/organization.js') }}"></script>
@endpush