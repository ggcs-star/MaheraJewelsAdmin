@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Suppliers</h4>
        <a href="{{ admin_route('suppliers.create') }}" class="btn btn-success">
            + Add Supplier
        </a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end" id="supplierFilterForm">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="position-relative">
                        <input
                            type="text"
                            name="search"
                            id="supplierSearch"
                            class="form-control pe-5"
                            placeholder="Name / Company / Phone"
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        <span
                            id="clearSupplierSearch"
                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                            style="cursor:pointer; display:none;"
                        >
                            ✕
                        </span>
                    </div>
                </div>
                
                <div class="col-md-4 d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label class="form-label fw-semibold">Supplier Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="manufacturer" {{ request('type') == 'manufacturer' ? 'selected' : '' }}>
                                Manufacturer
                            </option>
                            <option value="distributor" {{ request('type') == 'distributor' ? 'selected' : '' }}>
                                Distributor
                            </option>
                        </select>
                    </div>
                    
                    <button
                        type="button"
                        id="openSupplierFilterSidebar"
                        class="btn btn-secondary px-4"
                        style="height:38px"
                    >
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
 {{-- BULK DELETE FORM (ONLY BUTTON) --}}
<form method="POST"
      action="{{ route('admin.suppliers.bulk-delete') }}"
      id="supplierBulkDeleteForm">
    @csrf

    <button type="submit" class="btn btn-danger mb-3">
        Delete Selected
    </button>
</form>


{{-- TABLE CARD --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAllSuppliers">
                        </th>
                        <th width="60">#</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th width="150" class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr style="cursor:pointer"
                            onclick="window.location='{{ route('admin.suppliers.details', $supplier->id) }}'">

                            {{-- CHECKBOX (BULK DELETE) --}}
                            <td onclick="event.stopPropagation()">
                                <input type="checkbox"
                                       class="supplier-row-checkbox"
                                       name="ids[]"
                                       value="{{ $supplier->id }}"
                                       form="supplierBulkDeleteForm">
                            </td>

                            <td class="text-muted">
                                {{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-info text-dark px-3">
                                    {{ ucfirst($supplier->type) }}
                                </span>
                            </td>

                            <td class="fw-semibold">{{ $supplier->name }}</td>
                            <td>{{ $supplier->company_name ?? '-' }}</td>
                            <td>{{ $supplier->phone }}</td>

                            <td>
                                @if ($supplier->commission_type === 'percentage')
                                    <span class="text-primary fw-semibold">
                                        {{ $supplier->commission_value }}%
                                    </span>
                                @else
                                    <span class="text-success fw-semibold">
                                        ₹{{ number_format($supplier->commission_value, 2) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $supplier->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex gap-1 justify-content-center">

                                    <a href="{{ admin_route('suppliers.edit', $supplier) }}"
                                       class="btn btn-sm btn-light text-primary fw-semibold px-3">
                                        Edit
                                    </a>

                                    {{-- SINGLE DELETE --}}
                                    <form action="{{ admin_route('suppliers.destroy', $supplier) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this supplier?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-light text-danger fw-semibold px-3">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                No suppliers found matching your filters
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($suppliers->hasPages())
        <div class="card-footer bg-white">
            {{ $suppliers->withQueryString()->links() }}
        </div>
    @endif
</div>

    
    <div id="supplierFilterSidebar" class="filter-sidebar">
        <div class="filter-sidebar-header">
            <h5 class="mb-0">Advanced Filter</h5>
            <button type="button" id="closeSupplierFilterSidebar">✕</button>
        </div>
        <div class="filter-sidebar-body">
            <div class="mb-3">
                <label class="form-label">Field</label>
                <select id="advField" class="form-control">
                    <option value="name">Name</option>
                    <option value="company_name">Company</option>
                    <option value="email">Email</option>
                    <option value="phone">Phone</option>
                    <option value="type">Type</option>
                    <option value="status">Status</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Condition</label>
                <select id="advCondition" class="form-control">
                    <option value="like">Contains</option>
                    <option value="=">Equals</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Value</label>
                <input type="text" id="advValue" class="form-control">
            </div>
            <button type="button" id="applySupplierAdvancedFilter" class="btn btn-primary w-100">
                Apply Filter
            </button>
        </div>
    </div>
</div>

<script>
    const supplierSearch = document.getElementById('supplierSearch');
    const clearSupplierBtn = document.getElementById('clearSupplierSearch');
    const supplierForm = document.getElementById('supplierFilterForm');
    
    let supplierDebounce;
    
    function toggleSupplierClear() {
        clearSupplierBtn.style.display = supplierSearch.value ? 'block' : 'none';
    }
    
    supplierSearch.addEventListener('input', function () {
        toggleSupplierClear();
        clearTimeout(supplierDebounce);
        supplierDebounce = setTimeout(() => {
            supplierForm.submit();
        }, 400);
    });
    
    clearSupplierBtn.addEventListener('click', function () {
        supplierSearch.value = '';
        toggleSupplierClear();
        supplierForm.submit();
    });
    
    toggleSupplierClear();
    
    const supplierTypeSelect = supplierForm.querySelector('select[name="type"]');
    if (supplierTypeSelect) {
        supplierTypeSelect.addEventListener('change', function () {
            supplierForm.submit();
        });
    }
</script>

<script>
    const openSupplierBtn = document.getElementById('openSupplierFilterSidebar');
    const closeSupplierBtn = document.getElementById('closeSupplierFilterSidebar');
    const supplierSidebar = document.getElementById('supplierFilterSidebar');
    
    openSupplierBtn?.addEventListener('click', () => {
        supplierSidebar.classList.add('active');
    });
    
    closeSupplierBtn?.addEventListener('click', () => {
        supplierSidebar.classList.remove('active');
    });
    
    document.getElementById('applySupplierAdvancedFilter')
        ?.addEventListener('click', () => {
            const field = document.getElementById('advField').value;
            const condition = document.getElementById('advCondition').value;
            const value = document.getElementById('advValue').value;
            
            if (!value) return;
            
            const url = new URL(window.location.href);
            url.searchParams.set('adv_field', field);
            url.searchParams.set('adv_condition', condition);
            url.searchParams.set('adv_value', value);
            window.location.href = url.toString();
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllSuppliers');
        const bulkDeleteForm = document.getElementById('supplierBulkDeleteForm');
        const rowCheckboxes = document.querySelectorAll('.supplier-row-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });
        }
        
        rowCheckboxes.forEach(cb => {
            cb.addEventListener('click', e => {
                e.stopPropagation();
            });
            cb.addEventListener('mousedown', e => {
                e.stopPropagation();
            });
        });
        
        if (bulkDeleteForm) {
            bulkDeleteForm.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.supplier-row-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one supplier to delete.');
                    return false;
                }
                return confirm('Are you sure you want to delete ' + checked.length + ' supplier(s)?');
            });
        }
    });
</script>
@endsection