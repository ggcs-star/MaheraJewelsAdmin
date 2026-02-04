@extends('layouts.admin')

@section('content')
<div class="invoice-page">

    <div class="invoice-container">

        <!-- Header -->
        <div class="invoice-header">
            <div>
                <h1>INVOICE</h1>
                <p class="invoice-number">
                    Invoice No:
                    <strong>#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </p>
            </div>

            <div class="invoice-meta">
                <a href="{{ admin_route('products.invoice.download', $product->id) }}"
                   class="btn btn-sm btn-dark">
                    Download PDF
                </a>
                <p class="invoice-date">
                    Date: {{ date('d M Y') }}
                </p>
            </div>
        </div>

        <hr>

        <!-- Info -->
        <div class="invoice-info">
            <div>
                <h6>Product Details</h6>
                <p><strong>Product:</strong> {{ $product->name }}</p>
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
            </div>

            <div class="text-end">
                <h6>Supplier</h6>
                <p>{{ $product->supplier?->name ?? 'N/A' }}</p>
                <p>{{ $product->supplier?->email ?? 'N/A' }}</p>
                <p>{{ $product->supplier?->phone ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Variant</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $variant)
                <tr>
                    <td>{{ $variant->variant_value }}</td>
                    <td class="text-center">{{ $variant->quantity }}</td>
                    <td class="text-end">
                        ₹{{ number_format($variant->purchase_price, 2) }}
                    </td>
                    <td class="text-end fw-semibold">
                        ₹{{ number_format($variant->total_price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-wrapper">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-end">
                        ₹{{ number_format($product->variants->sum('total_price'), 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td class="text-end">₹0.00</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="text-end">
                        ₹{{ number_format($product->variants->sum('total_price'), 2) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p>
                Thank you for your business.<br>
                <small>This is a system generated invoice.</small>
            </p>

            <small class="text-muted">
                Generated on {{ date('Y-m-d H:i:s') }}
            </small>
        </div>

    </div>
</div>

<style>
.invoice-page {
    background: #f3f5f9;
    padding: 40px 0;
    font-family: "Inter", "Segoe UI", sans-serif;
}

.invoice-container {
    background: #fff;
    max-width: 950px;
    margin: auto;
    padding: 48px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.invoice-header h1 {
    margin: 0;
    letter-spacing: 3px;
    font-size: 28px;
}

.invoice-number {
    color: #6b7280;
    font-size: 14px;
    margin-top: 4px;
}

.invoice-meta {
    text-align: right;
}

.invoice-date {
    margin-top: 8px;
    font-size: 14px;
    color: #6b7280;
}

.invoice-info {
    display: flex;
    justify-content: space-between;
    margin: 32px 0;
}

.invoice-info h6 {
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 10px;
}

.invoice-info p {
    margin-bottom: 4px;
    font-size: 14px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
}

.invoice-table th {
    border-bottom: 2px solid #111;
    padding: 12px;
    font-size: 13px;
    text-transform: uppercase;
}

.invoice-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}

.totals-wrapper {
    display: flex;
    justify-content: flex-end;
}

.totals-wrapper table {
    width: 320px;
}

.totals-wrapper td {
    padding: 8px 0;
    font-size: 14px;
}

.grand-total td {
    font-size: 18px;
    font-weight: 700;
    border-top: 2px solid #111;
    padding-top: 12px;
}

.invoice-footer {
    margin-top: 40px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    font-size: 13px;
}
</style>
@endsection
