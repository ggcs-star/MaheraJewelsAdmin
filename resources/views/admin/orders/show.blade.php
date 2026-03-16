@extends('layouts.admin')

@section('content')

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
src="{{ $item->product->image ?? '/images/no-image.png' }}"
width="60"
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

<div class="card shadow-sm mb-4">

<div class="card-body">

<p>Subtotal : ₹{{ $order->subtotal }}</p>

<p>Shipping : ₹{{ $order->shipping }}</p>

<hr>

<h5>Total : ₹{{ $order->total }}</h5>

</div>

</div>


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