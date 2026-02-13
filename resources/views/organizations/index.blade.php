@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-building text-primary me-2"></i> Organizations Management
            </h4>
            <p class="text-muted mb-0">Manage all organizations and their details</p>
        </div>
        <a href="{{ admin_route('organizations.create') }}" class="btn btn-primary px-4 fw-semibold">
            <i class="fas fa-plus-circle me-2"></i> Add Organization
        </a>
    </div>

    {{-- ================= FLASH MESSAGES ================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ================= FILTER BAR ================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" id="filterForm" class="row g-4 align-items-end">
                @csrf
                
                {{-- SEARCH --}}
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold small mb-2">
                            <i class="fas fa-search text-primary me-1"></i>Search Organizations
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="orgSearch"
                                class="form-control border-start-0"
                                placeholder="Search by name, email or mobile"
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                            @if(request('search'))
                            <button type="button" class="btn btn-outline-secondary border-start-0" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label fw-semibold small mb-2">
                            <i class="fas fa-toggle-on text-primary me-1"></i>Status
                        </label>
                        <select name="status" class="form-select auto-submit">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="filter-header border-bottom d-flex justify-content-between align-items-center p-3">
    <h6 class="fw-bold mb-0">
        <i class="fas fa-filter me-2"></i>Advanced Filters
    </h6>

    <button type="button"
        class="btn-close"
        id="closeAdvancedFilter"
        aria-label="Close">
    </button>
</div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-list text-primary me-2"></i> Organizations List
                    </h6>
                    <p class="text-muted small mb-0">
                        Showing {{ $organizations->firstItem() ?? 0 }}-{{ $organizations->lastItem() ?? 0 }} of {{ $organizations->total() }} organizations
                    </p>
                </div>
                
                <form method="POST" 
                      action="{{ admin_route('organizations.bulk-delete') }}" 
                      id="bulkDeleteForm" 
                      class="mb-0">
                    @csrf
                    <button type="button" class="btn btn-sm btn-danger" id="openBulkDeleteModal">
                        <i class="fas fa-trash-alt me-1"></i> Delete Selected
                    </button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            @if($organizations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="ps-4">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="60">ID</th>
                                <th>Logo</th>
                                <th>Organization Details</th>
                                <th>Contact Info</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($organizations as $organization)
                                <tr class="clickable-row"
                                    data-url="{{ admin_route('organizations.show', $organization) }}"
                                    style="cursor: pointer;">

                                    <td class="ps-4" onclick="event.stopPropagation()">
                                        <input type="checkbox"
                                               class="form-check-input row-checkbox"
                                               name="ids[]"
                                               value="{{ $organization->id }}"
                                               form="bulkDeleteForm">
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark fw-semibold">
                                            {{ $organization->id }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="logo-container"
                                            style="cursor: zoom-in;"
                                            onclick="event.stopPropagation(); 
                                                    showLogoModal('{{ $organization->logo_url }}', '{{ $organization->name }}');">

                                            @if($organization->logo_url)
                                                <img src="{{ $organization->logo_url }}"
                                                    alt="{{ $organization->name }} Logo"
                                                    class="rounded-circle border"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>


                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold text-dark d-block mb-1">
                                                    {{ $organization->name }}
                                                </span>
                                                <small class="text-muted">
                                                    @if($organization->city)
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $organization->city }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($organization->email)
                                                <small class="text-muted mb-1">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $organization->email }}
                                                </small>
                                            @endif
                                            @if($organization->mobile)
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $organization->mobile }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge {{ $organization->is_active ? 'bg-success bg-opacity-10 text-success border-success' : 'bg-danger bg-opacity-10 text-danger border-danger' }} px-3 py-1 rounded-pill">
                                            <i class="fas {{ $organization->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                            {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ admin_route('organizations.edit', $organization) }}"
                                               class="btn btn-sm btn-outline-primary px-3"
                                               onclick="event.stopPropagation()">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger px-3 openSingleDelete"
                                                    data-action="{{ admin_route('organizations.destroy', $organization) }}"
                                                    onclick="event.stopPropagation()">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No organizations found</h6>
                        <p class="text-muted small">Create your first organization to get started</p>
                        <a href="{{ admin_route('organizations.create') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-plus-circle me-1"></i> Add Organization
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if($organizations->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $organizations->firstItem() }} to {{ $organizations->lastItem() }} of {{ $organizations->total() }} entries
                    </div>
                    <div>
                        {{ $organizations->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
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
                    <h6 class="fw-bold mb-2" id="deleteModalText"></h6>
                    <p class="text-muted mb-0">This action cannot be undone.</p>
                </div>
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger px-4 fw-semibold" id="confirmDelete">
                    <i class="fas fa-trash-alt me-2"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================= IMAGE MODAL ================= --}}
<div class="modal fade" id="logoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="modalLogoImage" src="" class="img-fluid" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

{{-- ================= ADVANCED FILTER SIDEBAR ================= --}}
<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-header border-bottom">
    <div class="d-flex justify-content-between align-items-center p-3">
        <h6 class="fw-bold mb-0">
            <i class="fas fa-filter me-2"></i>Advanced Filters
        </h6>

        {{-- CLOSE ICON --}}
        <button type="button"
        class="btn-close"
        id="closeAdvancedFilter"
        aria-label="Close"></button>

    </div>
</div>



    <div class="p-4">
        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Filter Field</label>
            <select id="advField" class="form-select form-select-sm">
                <option value="name" {{ request('adv_field') == 'name' ? 'selected' : '' }}>Organization Name</option>
                <option value="email" {{ request('adv_field') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="mobile" {{ request('adv_field') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="city" {{ request('adv_field') == 'city' ? 'selected' : '' }}>City</option>
                <option value="address" {{ request('adv_field') == 'address' ? 'selected' : '' }}>Address</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small mb-2">Condition</label>
            <select id="advCondition" class="form-select form-select-sm">
                <option value="like" {{ request('adv_condition') == 'like' ? 'selected' : '' }}>Contains</option>
                <option value="=" {{ request('adv_condition') == '=' ? 'selected' : '' }}>Equals</option>
                <option value="!=" {{ request('adv_condition') == '!=' ? 'selected' : '' }}>Not Equal</option>
                <option value="starts_with" {{ request('adv_condition') == 'starts_with' ? 'selected' : '' }}>Starts With</option>
                <option value="ends_with" {{ request('adv_condition') == 'ends_with' ? 'selected' : '' }}>Ends With</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold small mb-2">Value</label>
            <input type="text" 
                   id="advValue" 
                   class="form-control form-control-sm" 
                   placeholder="Enter value"
                   value="{{ request('adv_value') }}">
        </div>

        <div class="d-grid gap-2">
            <button type="button" id="applyAdvancedFilter" class="btn btn-primary btn-sm">
                <i class="fas fa-check me-2"></i> Apply Filter
            </button>
            
            <button type="button" id="clearAdvancedFilter" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times me-2"></i> Clear Filter
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
function showLogoModal(imageUrl, name) {
    if (!imageUrl) return;

    const img = document.getElementById('modalLogoImage');
    img.src = imageUrl;
    img.alt = name;

    const modalEl = document.getElementById('logoModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}
// Close Advanced Filter sidebar
document.getElementById('closeAdvancedFilter')?.addEventListener('click', function () {
    document.getElementById('filterSidebar').classList.remove('show');
});

</script>


<script src="{{ asset('assets/js/admin/organization.js') }}"></script>
@endpush