@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Suppliers</h4>
        <a href="{{ admin_route('suppliers.create') }}" class="btn btn-success">
            + Add Supplier
        </a>
    </div>

    {{-- Filter Card --}}
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


                <div class="col-md-3">
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

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100">
                        Apply
                    </button>
                    <a href="{{ admin_route('suppliers.index') }}" class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
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
                        <tr
                            style="cursor:pointer"
                            onclick="window.location='{{ route('admin.suppliers.details', $supplier->id) }}'"
                        >

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
                                    <span class="badge rounded-pill
                                        {{ $supplier->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
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
        <button class="btn btn-sm btn-light text-danger fw-semibold px-3">
            Delete
        </button>
    </form>
</div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No suppliers found matching your filters
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Pagination --}}
        @if ($suppliers->hasPages())
            <div class="card-footer bg-white">
                {{ $suppliers->withQueryString()->links() }}
            </div>
        @endif
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
</script>

@endsection
