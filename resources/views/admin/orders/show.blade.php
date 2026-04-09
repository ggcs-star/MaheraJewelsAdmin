@extends('layouts.admin')

@section('content')

@php
use Illuminate\Support\Facades\Storage;

// 🔥 MAIN FIX (Checkout Data)
$meta = $order->payment->payment_meta ?? null;

$subtotal = $meta['subtotal'] ?? $order->subtotal;
$discount = $meta['discount'] ?? $order->discount;
$tax = $meta['tax'] ?? $order->tax;
$shipping = $meta['shipping'] ?? $order->shipping;
$platformFee = $meta['platform_fee'] ?? $order->platform_fee;
$total = $meta['total'] ?? $order->total;
@endphp

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="fw-bold">
Order ID : #{{ $order->order_number ?? 'N/A' }}
</h4>

<a href="{{ route('admin.orders.invoice',$order->id) }}"
target="_blank"
class="btn btn-danger">
Print Invoice
</a>

</div>

<div class="row">

<!-- ================= LEFT ================= -->

<div class="col-md-8">

<div class="card shadow-sm mb-4">

<div class="card-header fw-bold">
Order Items
</div>

<div class="card-body">

@forelse($order->items as $item)

<div class="d-flex mb-3 border-bottom pb-2">
<img
src="{{ 
    $item->image 
    ? (str_starts_with($item->image, 'http') ? $item->image : Storage::disk('s3')->url($item->image))
    : (optional($item->product)->image_url ? Storage::disk('s3')->url($item->product->image_url) : asset('images/no-image.png'))
}}"
width="60"
height="60"
style="object-fit: cover; border-radius: 8px;"
onerror="this.src='{{ asset('images/no-image.png') }}'"
/>
<div class="w-100">

<h6 class="mb-1">
{{ $item->product_name ?? optional($item->product)->name ?? 'Product' }}
</h6>

<div class="d-flex justify-content-between">

<span class="text-muted">
₹{{ number_format($item->price,2) }} × {{ $item->quantity }}
</span>

<span class="fw-bold">
₹{{ number_format($item->subtotal,2) }}
</span>

</div>

</div>

</div>

@empty

<p>No Items Found</p>

@endforelse

</div>

</div>

</div>


<!-- ================= RIGHT ================= -->

<div class="col-md-4">

<!-- 🔥 PRICE BREAKDOWN -->

<div class="card shadow-sm mb-4">

<div class="card-header fw-bold">
Price Details
</div>

<div class="card-body">

<div class="d-flex justify-content-between mb-2">
<span>Subtotal</span>
<span>₹{{ number_format($subtotal,2) }}</span>
</div>

@if($discount > 0)
<div class="d-flex justify-content-between mb-2 text-success">
<span>Discount</span>
<span>- ₹{{ number_format($discount,2) }}</span>
</div>
@endif

<div class="d-flex justify-content-between mb-2">
<span>Tax</span>
<span>₹{{ number_format($tax,2) }}</span>
</div>

<div class="d-flex justify-content-between mb-2">
<span>Shipping</span>
<span>₹{{ number_format($shipping,2) }}</span>
</div>

@if($platformFee > 0)
<div class="d-flex justify-content-between mb-2">
<span>Platform Fee</span>
<span>₹{{ number_format($platformFee,2) }}</span>
</div>
@endif

<hr>

<div class="d-flex justify-content-between fw-bold fs-5">
<span>Total</span>
<span>₹{{ number_format($total,2) }}</span>
</div>

</div>

</div>


<!-- ORDER STATUS -->

<div class="card shadow-sm mb-4">

<div class="card-header fw-bold">
Update Order Status
</div>

<div class="card-body">

<form method="POST" action="{{ route('admin.orders.updateStatus',$order->id) }}">
@csrf

<select name="status" class="form-control mb-3">

<option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
<option value="confirmed" {{ $order->status=='confirmed'?'selected':'' }}>Confirmed</option>
<option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
<option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
<option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
<option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>

</select>

<button class="btn btn-primary w-100">
Update Status
</button>

</form>

</div>

</div>


<!-- DELIVERY -->

<div class="card shadow-sm">

<div class="card-header fw-bold">
Delivery Information
</div>

<div class="card-body">

<h6>
{{ optional($order->shippingAddress)->full_name ?? 'N/A' }}
</h6>

<p>{{ optional($order->shippingAddress)->phone }}</p>

<p>
{{ optional($order->shippingAddress)->address_line_1 }}<br>
{{ optional($order->shippingAddress)->city }},
{{ optional($order->shippingAddress)->state }}
</p>

</div>

</div>

</div>

</div>

</div>

@endsection