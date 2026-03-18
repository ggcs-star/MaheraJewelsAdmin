<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->order_number }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .container {
            width: 800px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company {
            max-width: 60%;
        }

        .invoice-title {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background: #f5f5f5;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            margin-top: 20px;
            width: 320px;
            float: right;
        }

        .print-btn {
            margin: 20px 0;
        }

        .grand-total {
            font-weight: bold;
            font-size: 15px;
        }
    </style>
</head>

<body>

@php
    // 🔥 MAIN FIX (Checkout Data)
    $meta = $order->payment->payment_meta ?? null;

    $subtotal = $meta['subtotal'] ?? $order->subtotal;
    $discount = $meta['discount'] ?? $order->discount;
    $tax = $meta['tax'] ?? $order->tax;
    $shipping = $meta['shipping'] ?? $order->shipping;
    $platformFee = $meta['platform_fee'] ?? $order->platform_fee;
    $total = $meta['total'] ?? $order->total;
@endphp

<div class="container">

    <div class="print-btn">
        <button onclick="window.print()">Print Invoice</button>
    </div>

    <!-- HEADER -->
    <div class="header">

        <div class="company">
            <h2>{{ $company->name ?? 'Company Name' }}</h2>

            <p>
                {{ $company->address ?? '' }}<br>
                {{ $company->city ?? '' }}, {{ $company->state ?? '' }}<br>
                {{ $company->country ?? '' }} - {{ $company->pincode ?? '' }}
            </p>

            <p>
                Email: {{ $company->email ?? '' }}<br>
                Phone: {{ $company->mobile ?? '' }}
            </p>
        </div>

        <div class="invoice-title">
            <h2>INVOICE</h2>

            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>

            @php
            $statusLabels = [
                'pending' => 'Order Placed',
                'confirmed' => 'Order Confirmed',
                'processing' => 'Packing In Progress',
                'shipped' => 'Out for Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];
            @endphp

            <p>
                <strong>Status:</strong>
                <span style="font-weight: bold;">
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </span>
            </p>
        </div>

    </div>


    <!-- CUSTOMER -->
    <table>
        <tr>
            <td>
                <strong>Bill To:</strong><br>
                {{ optional($order->shippingAddress)->full_name }}<br>
                {{ optional($order->shippingAddress)->phone }}<br>
                {{ optional($order->shippingAddress)->address_line_1 }}<br>
                {{ optional($order->shippingAddress)->city }},
                {{ optional($order->shippingAddress)->state }}
            </td>
        </tr>
    </table>


    <!-- ITEMS -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->items as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>

                <td>
                    {{ $item->product_name ?? optional($item->product)->name }}
                </td>

                <td>₹{{ number_format($item->price, 2) }}</td>

                <td>{{ $item->quantity }}</td>

                <td class="text-right">
                    ₹{{ number_format($item->subtotal, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <!-- 🔥 TOTAL BREAKDOWN (CHECKOUT SAME) -->
    <table class="total-box">

        <tr>
            <td>Subtotal</td>
            <td class="text-right">₹{{ number_format($subtotal, 2) }}</td>
        </tr>

        @if($discount > 0)
        <tr>
            <td>Discount</td>
            <td class="text-right">- ₹{{ number_format($discount, 2) }}</td>
        </tr>
        @endif

        <tr>
            <td>Tax</td>
            <td class="text-right">₹{{ number_format($tax, 2) }}</td>
        </tr>

        <tr>
            <td>Shipping</td>
            <td class="text-right">₹{{ number_format($shipping, 2) }}</td>
        </tr>

        @if($platformFee > 0)
        <tr>
            <td>Platform Fee</td>
            <td class="text-right">₹{{ number_format($platformFee, 2) }}</td>
        </tr>
        @endif

        <tr class="grand-total">
            <td>Total</td>
            <td class="text-right">₹{{ number_format($total, 2) }}</td>
        </tr>
    </table>


    <div style="clear: both;"></div>

    <!-- FOOTER -->
    <p style="margin-top:50px;">
        Thank you for your business 🙏
    </p>

</div>

</body>
</html>