@extends('layouts.admin')

@section('content')

@php
use Illuminate\Support\Facades\Storage;
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

<!-- ================= LEFT SIDE ================= -->

<div class="col-md-8">

<div class="card shadow-sm mb-4">

<div class="card-header fw-bold">
Order Details
</div>

<div class="card-body">

@forelse($order->items as $item)

<div class="d-flex mb-3 border-bottom pb-2">

<img
src="{{ optional($item->product)->image ? Storage::disk('s3')->url(optional($item->product)->image) : asset('images/no-image.png') }}"
width="60"
height="60"
style="object-fit:cover"
class="me-3"
/>

<div>

<h6 class="mb-1">
{{ $item->product_name ?? optional($item->product)->name ?? 'Product' }}
</h6>

<span class="text-muted">
₹{{ $item->price ?? 0 }} × {{ $item->quantity ?? 0 }}
</span>

</div>

</div>

@empty

<p>No Items Found</p>

@endforelse

</div>

</div>

</div>


<!-- ================= RIGHT SIDE ================= -->

<div class="col-md-4">

<!-- ORDER TOTAL -->

<div class="card shadow-sm mb-4">

<div class="card-body">

<p>Subtotal : ₹{{ $order->subtotal ?? 0 }}</p>

<p>Shipping : ₹{{ $order->shipping ?? 0 }}</p>

<hr>

<h5>Total : ₹{{ $order->total ?? 0 }}</h5>

</div>

</div>


<!-- ORDER STATUS UPDATE -->

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


<!-- DELIVERY INFO -->

<div class="card shadow-sm">

<div class="card-header fw-bold">
Delivery Information
</div>

<div class="card-body">

<h6>
{{ optional($order->shippingAddress)->full_name ?? 'N/A' }}
</h6>

<p>
{{ optional($order->shippingAddress)->phone ?? '' }}
</p>

<p>
{{ optional($order->shippingAddress)->address_line_1 ?? '' }}
<br>
{{ optional($order->shippingAddress)->city ?? '' }},
{{ optional($order->shippingAddress)->state ?? '' }}
</p>

</div>

</div>

</div>

</div>

</div>

@endsection