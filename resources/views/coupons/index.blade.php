@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h4 class="mb-0 fw-bold">Coupons</h4>
        <p class="text-muted small mb-0">Manage all coupon offers</p>
    </div>

    <a href="{{ route('admin.coupons.push') }}" class="btn btn-primary px-4">
        <i class="fas fa-plus me-1"></i> Add Coupon
    </a>
</div>

{{-- ================= FILTER BAR ================= --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" id="filterForm" class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-md-4 position-relative">
                <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        id="couponSearch"
                        class="form-control border-start-0"
                        placeholder="Coupon name or code"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <span id="clearSearch"
                          class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                          style="cursor:pointer; display:none;">
                        <i class="fas fa-times-circle"></i>
                    </span>
                </div>
            </div>

            {{-- Type --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Type</label>
                <select name="coupon_type" class="form-select auto-submit">
                    <option value="">All</option>
                    <option value="NORMAL" {{ request('coupon_type')=='NORMAL'?'selected':'' }}>Normal</option>
                    <option value="BANK" {{ request('coupon_type')=='BANK'?'selected':'' }}>Bank</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                <select name="status" class="form-select auto-submit">
                    <option value="">All</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            {{-- Advanced Filter --}}
            <div class="col-md-2 text-end">
                <button type="button" id="openFilterSidebar" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sliders-h me-1"></i> Advanced
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ================= TABLE + BULK DELETE ================= --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Coupons List</strong>

   <form method="POST"
      action="{{ route('admin.coupons.bulk-delete') }}"
      id="bulkDeleteForm"
      class="mb-0">
    @csrf
    <button type="button"
        class="btn btn-sm btn-outline-danger"
        id="openBulkDeleteModal">
    <i class="fas fa-trash me-1"></i> Delete Selected
</button>

</form>



    </div>

    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>ID</th>
                    <th>Coupon</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Platforms</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <input type="checkbox"
                               class="row-checkbox"
                               name="ids[]"
                               value="{{ $coupon->id }}"
                               form="bulkDeleteForm">
                    </td>

                    <td>{{ $coupon->id }}</td>

                    <td>
                        <div class="fw-semibold">{{ $coupon->name }}</div>
                        @if($coupon->description)
                            <small class="text-muted">{{ $coupon->description }}</small>
                        @endif
                    </td>

                    <td><span class="badge bg-secondary">{{ $coupon->code }}</span></td>

                    <td>
                        <span class="badge {{ $coupon->coupon_type=='BANK'?'bg-info':'bg-dark' }}">
                            {{ ucfirst(strtolower($coupon->coupon_type)) }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex gap-2">
                            @foreach($coupon->platforms as $platform)
                                <span class="badge bg-light text-dark">
                                    {{ $platform->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>

                    <td>
                        <span class="badge {{ $coupon->is_active?'bg-success':'bg-danger' }}">
                            {{ $coupon->is_active?'Active':'Inactive' }}
                        </span>
                    </td>

                    <td class="text-end">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                           class="btn btn-sm btn-outline-primary">Edit</a>

                        <form method="POST"
      class="d-inline singleDeleteForm">
    @csrf
    @method('DELETE')

    <button type="button"
            class="btn btn-sm btn-outline-danger openSingleDeleteModal"
            data-action="{{ route('admin.coupons.destroy', $coupon->id) }}">
        Delete
    </button>
</form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        No coupons found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p id="deleteModalText">
    Are you sure you want to delete selected coupons?
</p>

        <small class="text-muted">This action cannot be undone.</small>
      </div>

      <div class="modal-footer">
        <button type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button type="button"
                class="btn btn-danger"
                id="confirmBulkDelete">
          Yes, Delete
        </button>
      </div>

    </div>
  </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $coupons->appends(request()->query())->links() }}
</div>

</div>

{{-- ================= ADVANCED FILTER SIDEBAR ================= --}}
<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-header">
        <strong>Advanced Filter</strong>
        <button id="closeFilterSidebar">×</button>
    </div>

    <div class="p-3">
        <select id="advField" class="form-select mb-3">
            <option value="name">Coupon Name</option>
            <option value="code">Code</option>
            <option value="coupon_type">Type</option>
        </select>

        <select id="advCondition" class="form-select mb-3">
            <option value="like">Contains</option>
            <option value="=">Equals</option>
        </select>

        <input type="text" id="advValue" class="form-control mb-3" placeholder="Enter value">

        <button id="applyAdvancedFilter" class="btn btn-primary w-100">
            Apply Filter
        </button>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filterForm');
    const search = document.getElementById('couponSearch');
    const clear = document.getElementById('clearSearch');

    function toggleClear(){
        clear.style.display = search.value ? 'block' : 'none';
    }
    toggleClear();

    let timer;
    search.addEventListener('input', () => {
        toggleClear();
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 400);
    });

    clear.onclick = () => { search.value=''; form.submit(); };

    document.querySelectorAll('.auto-submit')
        .forEach(el => el.onchange = () => form.submit());

    document.getElementById('selectAll').onclick = e => {
        document.querySelectorAll('.row-checkbox')
            .forEach(cb => cb.checked = e.target.checked);
    };

    // Advanced filter
    document.getElementById('openFilterSidebar').onclick = () =>
        document.getElementById('filterSidebar').classList.add('open');

    document.getElementById('closeFilterSidebar').onclick = () =>
        document.getElementById('filterSidebar').classList.remove('open');

   // ===== ADVANCED FILTER (FIXED) =====
const advField = document.getElementById('advField');
const advCondition = document.getElementById('advCondition');
const advValue = document.getElementById('advValue');

document.getElementById('applyAdvancedFilter').onclick = () => {

    const f = advField.value;
    const c = advCondition.value;
    const v = advValue.value.trim();

    if (!v) return;

    const params = new URLSearchParams(window.location.search);

    params.set('adv_field', f);
    params.set('adv_condition', c);
    params.set('adv_value', v);

    window.location = `?${params.toString()}`;
};

});
</script>

<style>
.filter-sidebar{
    position:fixed;top:0;right:-360px;width:360px;height:100%;
    background:#fff;box-shadow:-4px 0 10px rgba(0,0,0,.1);
    transition:.3s;z-index:9999;
}
.filter-sidebar.open{right:0}
.filter-header{
    padding:15px;border-bottom:1px solid #ddd;
    display:flex;justify-content:space-between;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const bulkBtn = document.getElementById('openBulkDeleteModal');
    const confirmBtn = document.getElementById('confirmBulkDelete');
    const bulkForm = document.getElementById('bulkDeleteForm');
    const modalEl = document.getElementById('bulkDeleteModal');
    const modalText = document.getElementById('deleteModalText');

    let activeSingleForm = null; // 👈 track single delete

    /* ================= BULK DELETE ================= */
    bulkBtn.addEventListener('click', () => {
        const checked = document.querySelectorAll('.row-checkbox:checked');

        if (checked.length === 0) {
            alert('Please select at least one coupon');
            return;
        }

        activeSingleForm = null; // important
        modalText.innerText =
            `Are you sure you want to delete ${checked.length} selected coupon(s)?`;

        new bootstrap.Modal(modalEl).show();
    });

    /* ================= SINGLE DELETE ================= */
    document.querySelectorAll('.openSingleDeleteModal').forEach(btn => {
        btn.addEventListener('click', () => {

            activeSingleForm = btn.closest('.singleDeleteForm');
            activeSingleForm.action = btn.dataset.action;

            modalText.innerText =
                'Are you sure you want to delete this coupon?';

            new bootstrap.Modal(modalEl).show();
        });
    });

    /* ================= CONFIRM DELETE ================= */
    confirmBtn.addEventListener('click', () => {
        if (activeSingleForm) {
            activeSingleForm.submit(); // single delete
        } else {
            bulkForm.submit(); // bulk delete
        }
    });

});
</script>

@endsection
