@extends('layouts.admin')

@section('content')
{{-- AUTO PRINT WHEN ?print=1 --}}
@if(request()->has('print'))
<script>
    window.onload = () => window.print();
</script>
@endif

{{-- AUTO DOWNLOAD TRIGGER --}}
@if(request()->has('download'))
<script>
    window.onload = () => {
        window.print();
        document.title = "Invoice-{{ request()->route('invoice') ?? 'download' }}";
    };
</script>
@endif

<div class="container-fluid">
{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="fas fa-file-invoice me-2 text-primary"></i>
            Manage Invoice
        </h4>
        <small class="text-muted">Manage your invoice</small>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ admin_route('invoices.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> New Invoice
        </a>
        
    </div>
</div>

{{-- FILTER --}}
<div class="card mb-3">
    <div class="card-body">
        <form id="invoiceFilterForm"
            class="row g-2 align-items-end"
            method="GET"
            action="{{ admin_route('invoices.index') }}">
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date"
                    name="start_date"
                    id="startDate"
                    value="{{ request('start_date') }}"
                    class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date"
                    name="end_date"
                    id="endDate"
                    value="{{ request('end_date') }}"
                    class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <div class="position-relative">
                    <input type="text"
                           name="search"
                           id="invoiceSearch"
                           value="{{ request('search') }}"
                           class="form-control pe-5"
                           placeholder="Invoice / Customer">
                    <span id="clearSearch"
                          style="display:none;
                                 position:absolute;
                                 right:12px;
                                 top:50%;
                                 transform:translateY(-50%);
                                 cursor:pointer;
                                 font-size:18px;
                                 color:#888;">
                        ×
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100">
                    <i class="fas fa-search"></i> Find
                </button>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">SL.</th>
                    <th>Invoice No</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th class="text-end">Total Amount</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $index => $invoice)
                <tr style="cursor:pointer"
                    onclick="window.location='{{ admin_route('invoices.show', $invoice->id) }}'">
                    <td>{{ $invoices->firstItem() + $index }}</td>
                    <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->customer_name ?? 'Walking Customer' }}</td>
                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td class="text-end fw-semibold">₹{{ number_format($invoice->grand_total, 2) }}</td>
                    <td class="text-center">
                        <a href="{{ admin_route('invoices.edit', $invoice->id) }}"
                           class="btn btn-warning btn-sm"
                           title="Edit"
                           onclick="event.stopPropagation()">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ admin_route('invoices.show', $invoice->id) }}?print=1"
                           class="btn btn-primary btn-sm"
                           target="_blank"
                           onclick="event.stopPropagation()">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="{{ admin_route('invoices.show', $invoice->id) }}?download=1"
                           class="btn btn-success btn-sm"
                           target="_blank"
                           onclick="event.stopPropagation()">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="4" class="text-end">Total:</td>
                    <td class="text-end">₹{{ number_format($totalAmount, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $invoices->links() }}
</div>
</div>
@endsection

@push('scripts')
<script>
// ================ FILTER SEARCH CODE (Aapka original) ================
document.addEventListener('DOMContentLoaded', () => {
    const form      = document.getElementById('invoiceFilterForm');
    const search    = document.getElementById('invoiceSearch');
    const clear     = document.getElementById('clearSearch');
    const startDate = document.getElementById('startDate');
    const endDate   = document.getElementById('endDate');

    if (!form || !search) return;

    let timer;
    const delay = 400;

    const submitOrReset = () => {
        const hasValue = search.value.trim() || startDate?.value || endDate?.value;
        if (!hasValue) {
            window.location = form.action;
        } else {
            form.submit();
        }
    };

    search.addEventListener('input', () => {
        clear.style.display = search.value ? 'block' : 'none';
        clearTimeout(timer);
        timer = setTimeout(() => {
            submitOrReset();
        }, delay);
    });

    clear?.addEventListener('click', () => {
        search.value = '';
        if (startDate) startDate.value = '';
        if (endDate) endDate.value = '';
        clear.style.display = 'none';
        submitOrReset();
    });

    startDate?.addEventListener('change', submitOrReset);
    endDate?.addEventListener('change', submitOrReset);

    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    if (search.value || startDate?.value || endDate?.value) {
        clear.style.display = 'block';
    }
});
</script>
@endpush