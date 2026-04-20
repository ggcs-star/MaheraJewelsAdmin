@extends('layouts.admin.admin-settings')

@push('scripts')
    <script src="{{ asset('assets/js/admin/warehouses.js') }}"></script>
@endpush

@section('settings-content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold" style="color: #1e293b;">Warehouses</h4>
        <a href="{{ admin_route('warehouses.create') }}" class="btn" style="background: var(--primary-light); color: white; border: none; border-radius: 8px; padding: 8px 20px;">
            + Add Warehouse
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
@if(session('success'))
    <div id="flashMessage" class="alert alert-success mb-4" role="alert" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; border-radius: 12px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="flashMessage" class="alert alert-danger mb-4" role="alert" style="background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; border-radius: 12px;">
        {{ session('error') }}
    </div>
@endif


    {{-- SEARCH + CITY + FILTER --}}
    <div class="card shadow-sm mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0;">
        <div class="card-body">
            <form method="GET" id="warehouseFilterForm" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="color: #475569; font-size: 12px;">Search</label>
                    <div class="position-relative">
                        <input type="text"
                               name="search"
                               id="warehouseSearch"
                               class="form-control pe-5"
                               placeholder="Name / Code / Manager"
                               value="{{ request('search') }}"
                               autocomplete="off"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        <span id="clearWarehouseSearch"
                              class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                              style="cursor:pointer; display:none;">
                            ✕
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="color: #475569; font-size: 12px;">City</label>
                    <select name="city" id="warehouseCity" class="form-select" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
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
                            class="btn w-100" style="background: var(--primary-light); color: white; border: none; border-radius: 8px; padding: 8px 12px;">
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
                class="btn mb-3" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 6px 16px;">
            Delete Selected
        </button>
    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #f8fafc;">
                  <tr>
                        <th width="40" class="ps-3 py-2">
                            <input type="checkbox" id="selectAllWarehouses" style="border-radius: 4px; border: 1px solid #cbd5e1;">
                        </th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">ID</th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">Code</th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">Name</th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">City</th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">Manager</th>
                        <th class="py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">Status</th>
                        <th width="150" class="text-center py-2" style="font-size: 12px; font-weight: 600; color: #64748b;">Actions</th>
                    </tr>
                </thead>

              <tbody>
                @forelse($warehouses as $warehouse)
                <tr style="cursor:pointer"
                    onclick="window.location='{{ admin_route('warehouses.show', $warehouse) }}'">
                        <td class="ps-3" onclick="event.stopPropagation()">
                            <input type="checkbox"
                                   class="warehouse-row-checkbox"
                                   name="ids[]"
                                   value="{{ $warehouse->id }}"
                                   form="warehouseBulkDeleteForm"
                                   style="border-radius: 4px; border: 1px solid #cbd5e1;">
                        </td>

                        <td>{{ $warehouse->id }}</td>
                        <td>{{ $warehouse->code }}</td>
                        <td class="fw-semibold" style="color: #1e293b;">{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->city ?? '-' }}</td>
                        <td>{{ $warehouse->manager_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $warehouse->status === 'active' ? 'bg-success' : 'bg-secondary' }}" style="padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </td>

                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ admin_route('warehouses.edit',$warehouse) }}"
                                class="btn btn-sm" style="background: #f1f5f9; color: var(--primary-light); border: none; border-radius: 6px; padding: 5px 12px;">
                                    Edit
                                </a>

                                <form action="{{ admin_route('warehouses.destroy', $warehouse) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this warehouse?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm" style="background: #f1f5f9; color: #dc2626; border: none; border-radius: 6px; padding: 5px 12px;">
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
    <div id="warehouseFilterSidebar" class="filter-sidebar" style="position: fixed; top: 0; right: -380px; width: 380px; height: 100%; background: white; box-shadow: -2px 0 8px rgba(0,0,0,0.1); transition: right 0.3s ease; z-index: 1050;">
        <div class="filter-sidebar-header" style="border-bottom: 1px solid #e2e8f0; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; color: #1e293b;">Advanced Filter</h5>
            <button type="button" id="closeWarehouseFilterSidebar" style="background: none; border: none; font-size: 20px; cursor: pointer;">✕</button>
        </div>

        <div class="filter-sidebar-body" style="padding: 20px;">
            <div class="mb-3">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 12px; color: #475569;">Field</label>
                <select id="advField" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; width: 100%;">
                    <option value="name">Name</option>
                    <option value="code">Code</option>
                    <option value="city">City</option>
                    <option value="manager_name">Manager</option>
                    <option value="status">Status</option>
                </select>
            </div>

            <div class="mb-3">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 12px; color: #475569;">Condition</label>
                <select id="advCondition" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; width: 100%;">
                    <option value="like">Contains</option>
                    <option value="=">Equals</option>
                    <option value="!=">Not Equals</option>
                    <option value="starts_with">Starts With</option>
                    <option value="ends_with">Ends With</option>
                </select>
            </div>

            <div class="mb-3">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 12px; color: #475569;">Value</label>
                <input type="text" id="advValue" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; width: 100%;">
            </div>

            <button type="button"
                    id="applyWarehouseAdvancedFilter"
                    class="btn w-100" style="background: var(--primary-light); color: white; border: none; border-radius: 8px; padding: 10px; margin-top: 10px;">
                Apply Filter
            </button>
            <button type="button"
                    id="clearWarehouseAdvancedFilter"
                    class="btn w-100 mt-2" style="background: #f1f5f9; color: #64748b; border: none; border-radius: 8px; padding: 10px;">
                Clear Filter
            </button>
        </div>
    </div>

</div>

<style>
.filter-sidebar.open {
    right: 0 !important;
}
</style>
@endsection