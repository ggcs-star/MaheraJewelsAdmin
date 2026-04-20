@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Banks</h1>
            <p class="text-gray-500 text-xs mt-0.5">Manage all banks</p>
        </div>
        <a href="{{ admin_route('banks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Bank
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                name="search"
                                id="bankSearch"
                                class="w-full pl-8 pr-8 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                                placeholder="Bank name or code..."
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                            <span
                                id="clearSearch"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer text-xs"
                                style="display:none;"
                            >
                                ✕
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white auto-submit">
                            <option value="">All</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button
                            type="button"
                            id="openFilterSidebar"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all" style="background: #8B2452; color: white;"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Advanced Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAll" class="w-3.5 h-3.5 rounded border-gray-300 text-[#8B2452] focus:ring-[#8B2452] focus:ring-offset-0">
                <span class="text-xs text-gray-500">Select All</span>
            </div>
            <button type="button" id="openBulkDeleteModal" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Selected
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left w-10">
                            <span class="sr-only">Select</span>
                        </th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Bank Name</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-24">Code</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-500 uppercase tracking-wider w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($banks as $i => $bank)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2">
                                <input type="checkbox"
                                       class="row-checkbox w-3.5 h-3.5 rounded border-gray-300 text-[#8B2452] focus:ring-[#8B2452] focus:ring-offset-0"
                                       name="ids[]"
                                       value="{{ $bank->id }}"
                                       form="bulkDeleteForm">
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-500">{{ $banks->firstItem() + $i }}</td>
                            <td class="px-3 py-2">
                                <span class="text-sm font-semibold text-gray-800">{{ $bank->name }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded">{{ $bank->code }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium {{ $bank->status == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $bank->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ admin_route('banks.edit', $bank) }}" class="p-1 text-gray-400 hover:text-[#8B2452] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                            class="openSingleDeleteModal p-1 text-gray-400 hover:text-red-600 transition-colors"
                                            data-action="{{ admin_route('banks.destroy', $bank) }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6" />
                                </svg>
                                <p class="text-gray-500 text-xs">No banks found matching your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banks->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $banks->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <strong>Advanced Filter</strong>
        <button id="closeFilterSidebar">×</button>
    </div>
    <div class="filter-sidebar-body">
        <select id="advField" class="form-select mb-3">
            <option value="name">Bank Name</option>
            <option value="code">Code</option>
            <option value="status">Status</option>
        </select>
        <select id="advCondition" class="form-select mb-3">
            <option value="like">Contains</option>
            <option value="=">Equals</option>
        </select>
        <input type="text" id="advValue" class="form-control mb-3" placeholder="Enter value">
        <button id="applyAdvancedFilter" class="btn btn-primary w-100">Apply Filter</button>
        <button id="clearAdvancedFilter" class="btn btn-outline-secondary w-100 mt-2">Clear Filter</button>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalText"></p>
                <small class="text-muted">This action cannot be undone.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ admin_route('banks.bulk-delete') }}" id="bulkDeleteForm">
    @csrf
</form>

<style>
    .filter-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 320px;
        height: 100%;
        background: white;
        box-shadow: -2px 0 8px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 1000;
    }
    .filter-sidebar.open {
        transform: translateX(0);
    }
    .filter-sidebar-header {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .filter-sidebar-body {
        padding: 15px;
    }
    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const clearAdvancedBtn = document.getElementById('clearAdvancedFilter');
    if (clearAdvancedBtn) {
        clearAdvancedBtn.onclick = () => {
            document.getElementById('advField').selectedIndex = 0;
            document.getElementById('advCondition').selectedIndex = 0;
            document.getElementById('advValue').value = '';
            const params = new URLSearchParams(window.location.search);
            params.delete('adv_field');
            params.delete('adv_condition');
            params.delete('adv_value');
            window.location = `?${params.toString()}`;
        };
    }

    const form = document.getElementById('filterForm');
    const search = document.getElementById('bankSearch');
    const clear = document.getElementById('clearSearch');

    function toggleClear() {
        clear.style.display = search.value ? 'block' : 'none';
    }
    toggleClear();

    let timer;
    search.addEventListener('input', () => {
        toggleClear();
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 400);
    });

    clear.onclick = () => {
        search.value = '';
        form.submit();
    };

    document.querySelectorAll('.auto-submit').forEach(el => el.onchange = () => form.submit());

    document.getElementById('selectAll').onclick = e => {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
    };

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const modalText = document.getElementById('deleteModalText');
    const confirmBtn = document.getElementById('confirmDelete');
    const bulkForm = document.getElementById('bulkDeleteForm');
    let activeForm = null;

    document.getElementById('openBulkDeleteModal').onclick = () => {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (!checked.length) {
            alert('Please select at least one bank');
            return;
        }
        activeForm = null;
        modalText.innerText = `Are you sure you want to delete ${checked.length} selected bank(s)?`;
        modal.show();
    };

    document.querySelectorAll('.openSingleDeleteModal').forEach(btn => {
        btn.onclick = () => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = btn.dataset.action;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            document.body.appendChild(form);
            activeForm = form;
            modalText.innerText = 'Are you sure you want to delete this bank?';
            modal.show();
        };
    });

    confirmBtn.onclick = () => {
        if (activeForm) {
            activeForm.submit();
        } else {
            bulkForm.submit();
        }
    };

    const openFilter = document.getElementById('openFilterSidebar');
    const closeFilter = document.getElementById('closeFilterSidebar');
    const sidebar = document.getElementById('filterSidebar');

    if (openFilter && closeFilter && sidebar) {
        openFilter.onclick = () => sidebar.classList.add('open');
        closeFilter.onclick = () => sidebar.classList.remove('open');
    }

    const applyBtn = document.getElementById('applyAdvancedFilter');
    if (applyBtn) {
        applyBtn.onclick = () => {
            const f = document.getElementById('advField').value;
            const c = document.getElementById('advCondition').value;
            const v = document.getElementById('advValue').value.trim();
            if (!v) return;
            const params = new URLSearchParams(window.location.search);
            params.set('adv_field', f);
            params.set('adv_condition', c);
            params.set('adv_value', v);
            window.location = `?${params.toString()}`;
        };
    }
});
</script>
@endsection