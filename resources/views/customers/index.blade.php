@extends('layouts.admin')

@section('content')
<div class="container-fluid">

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Customers</h4>
        <small class="text-muted">Manage customers</small>
    </div>
    <a href="{{ admin_route('customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Customer
    </a>
</div>

{{-- FILTER BAR --}}
<div class="card mb-3">
    <div class="card-body row g-3 align-items-end">

        <div class="col-md-4">
            <label class="form-label fw-semibold small">Search</label>
            <input type="text"
                   id="customerSearch"
                   class="form-control"
                   placeholder="Search name / email / mobile"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold small">Status</label>
            <select id="statusFilter" class="form-select">
                <option value="">All</option>
                <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-outline-primary w-100" id="openAdvancedFilter">
                <i class="fas fa-filter me-1"></i> Advanced Filter
            </button>
        </div>

       

    </div>
</div>

{{-- TABLE --}}
<form method="POST" action="{{ admin_route('customers.bulk-delete') }}" id="bulkDeleteForm">
@csrf

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Customers List</span>
        <button type="button" class="btn btn-sm btn-danger" id="openBulkDelete">
            <i class="fas fa-trash me-1"></i> Delete Selected
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
            @foreach($customers as $customer)
                <tr class="clickable-row"
                    data-url="{{ admin_route('customers.show',$customer) }}">

                    <td onclick="event.stopPropagation()">
                        <input type="checkbox"
                               name="ids[]"
                               value="{{ $customer->id }}"
                               class="row-checkbox">
                    </td>

                    <td>
                        <div class="fw-semibold">{{ $customer->name }}</div>
                        <small class="text-muted">{{ $customer->city }}</small>
                    </td>

                    <td>
                        <small class="d-block">{{ $customer->email }}</small>
                        <small>{{ $customer->mobile }}</small>
                    </td>

                    <td>
                        <span class="badge {{ $customer->is_active ? 'bg-success':'bg-secondary' }}">
                            {{ $customer->is_active ? 'Active':'Inactive' }}
                        </span>
                    </td>

                    <td class="text-end" onclick="event.stopPropagation()">
                        <a href="{{ admin_route('customers.edit',$customer) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>

                        <button type="button"
                                class="btn btn-sm btn-outline-danger openSingleDelete"
                                data-url="{{ admin_route('customers.destroy',$customer) }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $customers->links() }}
    </div>
</div>
</form>

</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
            <i class="fas fa-triangle-exclamation me-1"></i> Confirm Delete
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-0">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

{{-- ADVANCED FILTER SIDEBAR --}}
<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-header d-flex justify-content-between align-items-center p-3">
        <strong>
            <i class="fas fa-filter me-2"></i> Advanced Filter
        </strong>
        <button type="button" class="btn btn-sm btn-link text-muted p-0" id="closeAdvancedFilter">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    <div class="p-3">
        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Filter Field</label>
            <select id="advField" class="form-select">
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="mobile">Mobile</option>
                <option value="city">City</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Condition</label>
            <select id="advCondition" class="form-select">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equal</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Value</label>
            <input type="text" 
                   id="advValue" 
                   class="form-control" 
                   placeholder="Enter value..."
                   value="{{ request('adv_value') }}">
        </div>

        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary" id="applyAdvancedFilter">
                <i class="fas fa-check me-2"></i> Apply Filter
            </button>
            <button type="button" class="btn btn-outline-secondary" id="clearAdvancedFilter">
                <i class="fas fa-times me-2"></i> Clear Filter
            </button>
        </div>
    </div>
</div>

{{-- Overlay --}}
<div id="filterOverlay" class="filter-overlay"></div>
@endsection

@push('styles')
<!-- <style>
/* Advanced Filter Sidebar Styles */
.filter-sidebar {
    position: fixed;
    top: 0;
    right: -380px;
    width: 350px;
    height: 100vh;
    background: white;
    box-shadow: -3px 0 15px rgba(0, 0, 0, 0.1);
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
    background: rgba(0, 0, 0, 0.5);
    z-index: 1059;
    display: none;
}

.filter-overlay.show {
    display: block;
}
</style> -->
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/customer.js') }}"></script>
@endpush