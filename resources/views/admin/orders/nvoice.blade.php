<!DOCTYPE html>
<html>

<head>

<title>Invoice</title>

<style>

body{
font-family: Arial;
}

.invoice-box{
max-width:800px;
margin:auto;
padding:30px;
border:1px solid #eee;
}

table{
width:100%;
border-collapse: collapse;
}

td,th{
padding:10px;
border-bottom:1px solid #eee;
}

.total{
font-weight:bold;
font-size:18px;
}

@media print{
button{
display:none;
}
}

</style>

</head>

<body>

<div class="invoice-box">

<button onclick="window.print()">Print</button>

<h2>Invoice</h2>

<p>Order : {{ $order->order_number }}</p>

<hr>

<table>

<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
</tr>

@foreach($order->items as $item)

<tr>

<td>{{ $item->product_name ?? $item->product->name }}</td>

<td>{{ $item->quantity }}</td>

<td>₹{{ $item->price }}</td>

</tr>

@endforeach

</table>

<hr>

<p>Subtotal : ₹{{ $order->subtotal }}</p>

<p>Shipping : ₹{{ $order->shipping }}</p>

<p class="total">Total : ₹{{ $order->total }}</p>

</div>

</body>

</html>