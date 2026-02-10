@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
@endpush

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-ticket-alt text-primary me-2"></i>Coupons Management
        </h4>
        <p class="text-muted mb-0">Manage all coupon offers and promotions</p>
    </div>

    <a href="{{ route('admin.coupons.push') }}" class="btn btn-primary px-4 fw-semibold">
        <i class="fas fa-plus-circle me-2"></i> Create Coupon
    </a>
</div>

{{-- ================= FILTER BAR ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" id="filterForm" class="row g-4 align-items-end">
            
            {{-- Search --}}
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label fw-semibold small mb-2">
                        <i class="fas fa-search text-primary me-1"></i>Search Coupons
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            id="couponSearch"
                            class="form-control border-start-0"
                            placeholder="Search by name or code"
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        @if(request('search'))
                        <span class="input-group-text bg-light cursor-pointer" id="clearSearchBtn">
                            <i class="fas fa-times text-muted"></i>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Type --}}
            <div class="col-lg-3">
                <div class="form-group">
                    <label class="form-label fw-semibold small mb-2">
                        <i class="fas fa-tag text-primary me-1"></i>Coupon Type
                    </label>
                    <select name="coupon_type" class="form-select form-select-sm auto-submit">
                        <option value="">All Types</option>
                        <option value="NORMAL" {{ request('coupon_type')=='NORMAL'?'selected':'' }}>Coupon</option>
                        <option value="BANK" {{ request('coupon_type')=='BANK'?'selected':'' }}>Bank Offer</option>
                    </select>
                </div>
            </div>

            {{-- Status --}}
            <div class="col-lg-3">
                <div class="form-group">
                    <label class="form-label fw-semibold small mb-2">
                        <i class="fas fa-toggle-on text-primary me-1"></i>Status
                    </label>
                    <select name="status" class="form-select form-select-sm auto-submit">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                        <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                    </select>
                </div>
            </div>

            {{-- Filter Actions --}}
            <div class="col-lg-2">
                <div class="d-flex gap-2">
                  
                    <button type="button" id="openFilterSidebar" class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Advanced
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- ================= COUPONS TABLE ================= --}}
<div class="card border-0 shadow">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-list text-primary me-2"></i>Coupons List
                </h6>
                <p class="text-muted small mb-0">
                    Showing {{ $coupons->firstItem() ?? 0 }}-{{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} coupons
                </p>
            </div>
            
            <form method="POST" action="{{ route('admin.coupons.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
                @csrf
                <button type="button" class="btn btn-sm btn-danger" id="openBulkDeleteModal">
                    <i class="fas fa-trash-alt me-1"></i> Delete Selected
                </button>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-4">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>ID</th>
                        <th>Coupon Details</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Platforms</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox"
                                       class="form-check-input row-checkbox"
                                       name="ids[]"
                                       value="{{ $coupon->id }}"
                                       form="bulkDeleteForm">
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">{{ $coupon->id }}</span>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="mb-1">{{ $coupon->name }}</strong>
                                    @if($coupon->description)
                                        <small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                                    @endif
                                    <small class="text-muted mt-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ optional($coupon->starts_at)->format('M d, Y') }} - 
                                        {{ optional($coupon->expires_at)->format('M d, Y') }}
                                    </small>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark border px-3 py-1 rounded-pill">
                                    {{ $coupon->code }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $coupon->coupon_type=='BANK'?'bg-info bg-opacity-10 text-info border-info':'bg-dark bg-opacity-10 text-dark border-dark' }} px-3 py-1 rounded-pill">
                                    <i class="fas {{ $coupon->coupon_type=='BANK'?'fa-university':'fa-tag' }} me-1"></i>
                                    {{ ucfirst(strtolower($coupon->coupon_type)) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                    @foreach($coupon->platforms as $platform)
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="fas fa-store me-1"></i>{{ $platform->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td>
                                <span class="badge {{ $coupon->is_active?'bg-success bg-opacity-10 text-success border-success':'bg-danger bg-opacity-10 text-danger border-danger' }} px-3 py-1 rounded-pill">
                                    <i class="fas {{ $coupon->is_active?'fa-check-circle':'fa-times-circle' }} me-1"></i>
                                    {{ $coupon->is_active?'Active':'Inactive' }}
                                </span>
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                       class="btn btn-sm btn-outline-primary px-3">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>

                                    <form method="POST" class="d-inline singleDeleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger px-3 openSingleDeleteModal"
                                                data-action="{{ route('admin.coupons.destroy', $coupon->id) }}">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted mb-2">No coupons found</h6>
                                    <p class="text-muted small">Create your first coupon to get started</p>
                                    <a href="{{ route('admin.coupons.push') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus-circle me-1"></i> Create Coupon
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bulk Delete Modal --}}
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h6 class="fw-bold mb-2">Delete Selected Coupons</h6>
                    <p class="text-muted mb-0" id="deleteModalText">
                        Are you sure you want to delete selected coupons?
                    </p>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger px-4 fw-semibold" id="confirmBulkDelete">
                    <i class="fas fa-trash-alt me-2"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($coupons->hasPages())
<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Showing {{ $coupons->firstItem() }} to {{ $coupons->lastItem() }} of {{ $coupons->total() }} entries
        </div>
        <nav>
            {{ $coupons->appends(request()->query())->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
@endif

{{-- Advanced Filter Sidebar --}}
<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-header border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">
                <i class="fas fa-filter me-2"></i>Advanced Filters
            </h6>
            <button id="closeFilterSidebar" class="btn btn-sm btn-link text-muted">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
    </div>

    <div class="p-4">
        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Filter Field</label>
            <select id="advField" class="form-select form-select-sm">
                <option value="name">Coupon Name</option>
                <option value="code">Coupon Code</option>
                <option value="coupon_type">Coupon Type</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Condition</label>
            <select id="advCondition" class="form-select form-select-sm">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equal</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold small mb-2">Value</label>
            <input type="text" id="advValue" class="form-control form-control-sm" placeholder="Enter value">
        </div>

        <button id="applyAdvancedFilter"
            class="btn btn-primary btn-sm w-100 mb-2">
        <i class="fas fa-check me-2"></i> Apply Filter
        </button>

        <button id="clearAdvancedFilter"
                type="button"
                class="btn btn-outline-secondary btn-sm w-100">
            ✖ Clear Filter
        </button>

    </div>
</div>

</div>
@endsection