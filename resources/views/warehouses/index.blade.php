@extends('layouts.admin.admin-settings')

@push('scripts')
    <script src="{{ asset('assets/js/admin/warehouses.js') }}"></script>
@endpush

@section('settings-content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Warehouses</h4>
        <a href="{{ admin_route('warehouses.create') }}" class="btn btn-primary">
            + Add Warehouse
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
@if(session('success'))
    <div id="flashMessage"
         class="alert alert-success mb-4"
         role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="flashMessage"
         class="alert alert-danger mb-4"
         role="alert">
        {{ session('error') }}
    </div>
@endif


    {{-- SEARCH + CITY + FILTER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="warehouseFilterForm" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="position-relative">
                        <input type="text"
                               name="search"
                               id="warehouseSearch"
                               class="form-control pe-5"
                               placeholder="Name / Code / Manager"
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <span id="clearWarehouseSearch"
                              class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                              style="cursor:pointer; display:none;">
                            ✕
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">City</label>
                    <select name="city" id="warehouseCity" class="form-select">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}"
                                {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button"
                            id="openWarehouseFilterSidebar"
                            class="btn btn-secondary w-100">
                        Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- BULK DELETE BUTTON (UI ONLY) --}}
    <form method="POST"
          action="{{ route('admin.warehouses.bulk-delete') }}"
          id="warehouseBulkDeleteForm">
        @csrf

        <button type="button"
                id="bulkDeleteWarehouseBtn"
                class="btn btn-danger mb-3">
            Delete Selected
        </button>
    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAllWarehouses">
                        </th>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th width="150" class="text-center">Actions</th>
                    </tr>
                </thead>

              <tbody>
                @forelse($warehouses as $warehouse)
                <tr style="cursor:pointer"
                    onclick="window.location='{{ admin_route('warehouses.show', $warehouse) }}'">
                        <td onclick="event.stopPropagation()">
                            <input type="checkbox"
                                   class="warehouse-row-checkbox"
                                   name="ids[]"
                                   value="{{ $warehouse->id }}"
                                   form="warehouseBulkDeleteForm">
                        </td>

                        <td>{{ $warehouse->id }}</td>
                        <td>{{ $warehouse->code }}</td>
                        <td class="fw-semibold">{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->city ?? '-' }}</td>
                        <td>{{ $warehouse->manager_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $warehouse->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </td>

                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ admin_route('warehouses.edit',$warehouse) }}"
                                class="btn btn-sm btn-primary px-3">
                                    Edit
                                </a>

                                <form action="{{ admin_route('warehouses.destroy', $warehouse) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this warehouse?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger px-3">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8"
                            class="text-center py-4 text-muted">
                            No warehouses found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (UNCHANGED) --}}
        @if($warehouses->hasPages())
            <div class="card-footer bg-white">
                {{ $warehouses->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- ADVANCED FILTER SIDEBAR --}}
    <div id="warehouseFilterSidebar" class="filter-sidebar">
        <div class="filter-sidebar-header">
            <h5>Advanced Filter</h5>
            <button type="button" id="closeWarehouseFilterSidebar">✕</button>
        </div>

        <div class="filter-sidebar-body">
            <div class="mb-3">
                <label>Field</label>
                <select id="advField" class="form-control">
                    <option value="name">Name</option>
                    <option value="code">Code</option>
                    <option value="city">City</option>
                    <option value="manager_name">Manager</option>
                    <option value="status">Status</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Condition</label>
                <select id="advCondition" class="form-control">
                    <option value="like">Contains</option>
                    <option value="=">Equals</option>
                    <option value="!=">Not Equals</option>
                    <option value="starts_with">Starts With</option>
                    <option value="ends_with">Ends With</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Value</label>
                <input type="text" id="advValue" class="form-control">
            </div>

            <button type="button"
                    id="applyWarehouseAdvancedFilter"
                    class="btn btn-primary w-100">
                Apply Filter
            </button>
            <button type="button"
        id="clearWarehouseAdvancedFilter"
        class="btn btn-outline-secondary w-100 mt-2">
    Clear Filter
</button>

        </div>
    </div>

</div>
@endsection
