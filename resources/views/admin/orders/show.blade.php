@extends('layouts.admin')

@section('content')

@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="fw-bold">
Order ID : #{{ $order->order_number }}
</h4>

<a
href="{{ route('admin.orders.invoice',$order->id) }}"
target="_blank"
class="btn btn-danger">
Print Invoice
</a>

</div>


<div class="row">

<div class="col-md-8">

<div class="card shadow-sm mb-4">

<div class="card-header fw-bold">
Order Details
</div>

<div class="card-body">

@foreach($order->items as $item)

<div class="d-flex mb-3 border-bottom pb-2">

<img
src="{{ $item->product && $item->product->image ? Storage::disk('s3')->url($item->product->image) : asset('images/no-image.png') }}"
width="60"
height="60"
style="object-fit:cover"
class="me-3"
/>

<div>

<h6 class="mb-1">
{{ $item->product_name ?? $item->product->name }}
</h6>

<span class="text-muted">
₹{{ $item->price }} × {{ $item->quantity }}
</span>

</div>

</div>

@endforeach

</div>

</div>

</div>


<div class="col-md-4">

<!-- ORDER TOTAL -->

<div class="card shadow-sm mb-4">

<div class="card-body">

<p>Subtotal : ₹{{ $order->subtotal }}</p>

<p>Shipping : ₹{{ $order->shipping }}</p>

<hr>

<h5>Total : ₹{{ $order->total }}</h5>

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
@method('PUT')

<select name="status" class="form-control mb-3">

<option value="pending" {{ $order->status=='pending'?'selected':'' }}>
Pending
</option>

<option value="confirmed" {{ $order->status=='confirmed'?'selected':'' }}>
Confirmed
</option>

<option value="processing" {{ $order->status=='processing'?'selected':'' }}>
Processing
</option>

<option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>
Shipped
</option>

<option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>
Delivered
</option>

<option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>
Cancelled
</option>

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
{{ $order->shippingAddress->full_name ?? '' }}
</h6>

<p>
{{ $order->shippingAddress->phone ?? '' }}
</p>

<p>

{{ $order->shippingAddress->address_line_1 ?? '' }}

<br>

{{ $order->shippingAddress->city ?? '' }},
{{ $order->shippingAddress->state ?? '' }}

</p>

</div>

</div>

</div>

</div>

</div>

@endsection