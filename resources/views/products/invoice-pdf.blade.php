<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #111;
            margin: 0;
            padding: 40px;
        }

        /* Header */
        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .header table {
            width: 100%;
        }

        h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 3px;
        }

        .sub {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
        }

        .meta {
            text-align: right;
            font-size: 12px;
            color: #555;
        }

        hr {
            border: none;
            border-top: 1px solid #111;
            margin: 18px 0;
        }

        /* Info */
        .info table {
            width: 100%;
            margin-bottom: 28px;
        }

        .info h6 {
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .info p {
            margin: 3px 0;
            font-size: 13px;
        }

        /* Table */
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .items th {
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #111;
            padding: 10px;
            background: #f5f5f5;
        }

        .items td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals */
        .totals {
            width: 100%;
            margin-top: 10px;
        }

        .totals table {
            width: 38%;
            float: right;
        }

        .totals td {
            padding: 8px 0;
            font-size: 13px;
        }

        .grand td {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #111;
            padding-top: 12px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 25px;
            left: 40px;
            right: 40px;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1>INVOICE</h1>
                    <div class="sub">
                        Invoice #
                        {{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </td>
                <td class="meta">
                    Date: {{ now()->format('d M Y') }}
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <!-- Product & Supplier -->
    <div class="info">
        <table>
            <tr>
                <td width="50%">
                    <h6>Product Details</h6>
                    <p><strong>Product:</strong> {{ $product->name }}</p>
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                </td>
                <td width="50%" align="right">
                    <h6>Supplier</h6>
                    <p>{{ $product->supplier?->name ?? 'N/A' }}</p>
                    <p>{{ $product->supplier?->email ?? '' }}</p>
                    <p>{{ $product->supplier?->phone ?? '' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items -->
    <table class="items">
        <thead>
            <tr>
                <th>Variant</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->variants as $variant)
            <tr>
                <td>{{ $variant->variant_value }}</td>
                <td class="text-center">{{ $variant->quantity }}</td>
                <td class="text-right">
                    ₹{{ number_format($variant->purchase_price, 2) }}
                </td>
                <td class="text-right">
                    ₹{{ number_format($variant->total_price, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">
                    ₹{{ number_format($product->variants->sum('total_price'), 2) }}
                </td>
            </tr>
            <tr class="grand">
                <td>Grand Total</td>
                <td class="text-right">
                    ₹{{ number_format($product->variants->sum('total_price'), 2) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        This is a system generated invoice.
    </div>

</body>
</html>
