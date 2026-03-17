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

        .no-border {
            border: none !important;
        }

        .total-box {
            margin-top: 20px;
            width: 300px;
            float: right;
        }

        .print-btn {
            margin: 20px 0;
        }
    </style>
</head>

<body>

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

$statusColors = [
    'pending' => 'orange',
    'confirmed' => 'blue',
    'processing' => 'purple',
    'shipped' => 'brown',
    'delivered' => 'green',
    'cancelled' => 'red',
];
@endphp

<p>
    <strong>Status:</strong> 
    <span style="color: {{ $statusColors[$order->status] ?? 'black' }}; font-weight: bold;">
        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
    </span>
</p>        </div>

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

                <td>₹{{ $item->price }}</td>

                <td>{{ $item->quantity }}</td>

                <td class="text-right">
                    ₹{{ $item->price * $item->quantity }}
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>


    <!-- TOTAL -->
    <table class="total-box">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">₹{{ $order->subtotal }}</td>
        </tr>

        <tr>
            <td>Shipping</td>
            <td class="text-right">₹{{ $order->shipping }}</td>
        </tr>

        <tr>
            <th>Total</th>
            <th class="text-right">₹{{ $order->total }}</th>
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