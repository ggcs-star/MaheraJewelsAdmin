@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h4 class="mb-0 fw-bold">Banks</h4>
        <p class="text-muted small mb-0">Manage all banks</p>
    </div>

    <a href="{{ admin_route('banks.create') }}" class="btn btn-primary px-4">
        <i class="fas fa-plus me-1"></i> Add Bank
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" id="filterForm" class="row g-3 align-items-end">

            <div class="col-md-5 position-relative">
                <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                <input type="text"
                       name="search"
                       id="bankSearch"
                       class="form-control"
                       placeholder="Bank name or code"
                       value="{{ request('search') }}"
                       autocomplete="off">
                <span id="clearSearch"
                      class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                      style="cursor:pointer; display:none;">
                    ✕
                </span>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                <select name="status" class="form-select auto-submit">
                    <option value="">All</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2"></div>

            <div class="col-md-2 text-end">
                <button type="button"
                        id="openFilterSidebar"
                        class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sliders-h me-1"></i> Advanced
                </button>
            </div>

        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-2">
    <form method="POST"
          action="{{ admin_route('banks.bulk-delete') }}"
          id="bulkDeleteForm">
        @csrf
        <button type="button"
                class="btn btn-sm btn-outline-danger"
                id="openBulkDeleteModal">
            <i class="fas fa-trash me-1"></i> Delete Selected
        </button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th class="text-end" width="160">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($banks as $i => $bank)
                <tr>
                    <td>
                        <input type="checkbox"
                               class="row-checkbox"
                               name="ids[]"
                               value="{{ $bank->id }}"
                               form="bulkDeleteForm">
                    </td>
                    <td>{{ $banks->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $bank->name }}</td>
                    <td>{{ $bank->code }}</td>
                    <td>
                        <span class="badge {{ $bank->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $bank->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ admin_route('banks.edit', $bank) }}"
                           class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>

                        <form method="POST" class="d-inline singleDeleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger openSingleDeleteModal"
                                    data-action="{{ admin_route('banks.destroy', $bank) }}">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No banks found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
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

        <button id="applyAdvancedFilter" class="btn btn-primary w-100">
            Apply Filter
        </button>
        <button id="clearAdvancedFilter" class="btn btn-outline-secondary w-100 mt-2">
    Clear Filter
</button>

    </div>
</div>

<div class="mt-3">
    {{ $banks->appends(request()->query())->links() }}
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          Yes, Delete
        </button>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const clearAdvancedBtn = document.getElementById('clearAdvancedFilter');

if (clearAdvancedBtn) {
    clearAdvancedBtn.onclick = () => {

        // 1️⃣ input values clear
        document.getElementById('advField').selectedIndex = 0;
        document.getElementById('advCondition').selectedIndex = 0;
        document.getElementById('advValue').value = '';

        // 2️⃣ URL se advanced filters hatao
        const params = new URLSearchParams(window.location.search);
        params.delete('adv_field');
        params.delete('adv_condition');
        params.delete('adv_value');

        // 3️⃣ reload without filters
        window.location = `?${params.toString()}`;
    };
}

    const form = document.getElementById('filterForm');
    const search = document.getElementById('bankSearch');
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

    clear.onclick = () => {
        search.value = '';
        form.submit();
    };

    document.querySelectorAll('.auto-submit')
        .forEach(el => el.onchange = () => form.submit());

    document.getElementById('selectAll').onclick = e => {
        document.querySelectorAll('.row-checkbox')
            .forEach(cb => cb.checked = e.target.checked);
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
            activeForm = btn.closest('.singleDeleteForm');
            activeForm.action = btn.dataset.action;
            modalText.innerText = 'Are you sure you want to delete this bank?';
            modal.show();
        };
    });

    confirmBtn.onclick = () => {
        activeForm ? activeForm.submit() : bulkForm.submit();
    };
});
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
</script>
@endsection