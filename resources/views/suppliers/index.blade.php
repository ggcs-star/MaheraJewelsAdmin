@extends('layouts.admin')

@section('content')
@push('scripts')
    <script src="{{ asset('assets/js/admin/suppliers.js') }}"></script>
@endpush

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
<form method="POST"
      action="{{ route('admin.suppliers.bulk-delete') }}"
      id="supplierBulkDeleteForm">
    @csrf

    <button type="button"
        id="bulkDeleteBtn"
        class="btn btn-danger mb-3">
    Delete Selected
</button>
</form>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAllSuppliers">
                        </th>
                        <th width="60">Id</th>
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
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex gap-1 justify-content-center">

                                    <a href="{{ admin_route('suppliers.edit', $supplier) }}"
                                       class="btn btn-sm btn-light text-primary fw-semibold px-3">
                                        Edit
                                    </a>
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
                    <option value="!=">Not Equals</option>
                    <option value="starts_with">Starts With</option>
                    <option value="ends_with">Ends With</option>
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
@endsection