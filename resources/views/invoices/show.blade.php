@extends('layouts.admin')

@section('content')

{{-- AUTO PRINT WHEN ?print=1 --}}
@if(request()->has('print'))
<script>
    window.onload = () => window.print();
</script>
@endif

<div class="container my-4">

   <div class="no-print text-end mb-3">

  <a href="{{ admin_route('invoices.index') }}"
       class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <div class="no-print text-end mt-4">
    <button onclick="printInvoice()" class="btn btn-primary">
        Print
    </button>

    <button onclick="downloadPDF()" class="btn btn-success">
        Download PDF
    </button>
</div>

</div>


    {{-- ================= INVOICE WRAPPER ================= --}}
    <div class="invoice-wrapper bg-white p-4 shadow-sm">

        {{-- HEADER --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                @if($invoice->organization?->logo_url)
                    <img src="{{ $invoice->organization->logo_url }}"
                         alt="Logo"
                         style="max-height:80px">
                @endif
            </div>

            <div class="col-md-6 text-end">
                <h2 class="fw-bold mb-1">INVOICE</h2>
                <div><strong>No:</strong> {{ $invoice->invoice_number }}</div>
                <div>
                    <strong>Date:</strong>
                    {{ $invoice->invoice_date->format('d M Y') }}
                </div>
            </div>
        </div>

        <hr>

        {{-- BILLING --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="badge bg-success mb-2">Billing From</span>
                <h5 class="fw-bold">{{ $invoice->organization->name }}</h5>
                <div>{{ $invoice->organization->address }}</div>
                <div>{{ $invoice->organization->city }}, {{ $invoice->organization->state }}</div>
                <div>Mobile: {{ $invoice->organization->mobile }}</div>
                <div>Email: {{ $invoice->organization->email }}</div>
            </div>

            <div class="col-md-6 text-end">
                <span class="badge bg-success mb-2">Billing To</span>
                <h5 class="fw-bold">
                    {{ $invoice->customer_name ?? 'Walking Customer' }}
                </h5>
                @if($invoice->customer_mobile)
                    <div>Mobile: {{ $invoice->customer_mobile }}</div>
                @endif
                @if($invoice->customer_address)
                    <div>{{ $invoice->customer_address }}</div>
                @endif
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Item</th>
                        <th width="90" class="text-end">Qty</th>
                        <th width="120" class="text-end">Price</th>
                        <th width="140" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            {{ $item->product_name }}
                            <br>
                            <small class="text-muted">
                                {{ $item->variant_name }}
                            </small>
                        </td>
                        <td class="text-end">{{ $item->quantity }}</td>
                        <td class="text-end">₹ {{ number_format($item->price,2) }}</td>
                        <td class="text-end">₹ {{ number_format($item->total,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TOTALS --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <!-- <p class="fw-semibold mb-5">Received By</p>
                ________________________ -->
            </div>

            <div class="col-md-6">
                <table class="table table-borderless w-75 ms-auto">
                    <tr>
                        <td>Sub Total</td>
                        <td class="text-end">₹ {{ number_format($invoice->sub_total,2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td class="text-end">₹ {{ number_format($invoice->discount,2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td class="text-end">₹ {{ number_format($invoice->tax_amount,2) }}</td>
                    </tr>
                    <tr class="fw-bold border-top">
                        <td>Grand Total</td>
                        <td class="text-end">₹ {{ number_format($invoice->grand_total,2) }}</td>
                    </tr>
                    <tr>
                        <td>Paid</td>
                        <td class="text-end">₹ {{ number_format($invoice->paid_amount,2) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Due</td>
                        <td class="text-end text-danger">
                            ₹ {{ number_format($invoice->due_amount,2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
function printInvoice() {
    window.print();
}

function downloadPDF() {
    const originalTitle = document.title;
    document.title = "{{ $invoice->invoice_number }}";

    window.print();

    setTimeout(() => {
        document.title = originalTitle;
    }, 1000);
}
</script>
@endpush
<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .invoice-wrapper {
        box-shadow: none !important;
        margin: 0;
        padding: 0;
    }
}
</style>
