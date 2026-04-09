@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

<h4 class="fw-bold mb-4">Orders Management</h4>

<div class="row mb-4">

<div class="col-md-3">
<div class="card shadow-sm bg-primary text-white border-0">
<div class="card-body">
<h6>Total Orders</h6>
<h3>{{ $stats['total'] ?? 0 }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm bg-warning text-white border-0">
<div class="card-body">
<h6>Pending</h6>
<h3>{{ $stats['pending'] ?? 0 }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm bg-info text-white border-0">
<div class="card-body">
<h6>Processing</h6>
<h3>{{ $stats['processing'] ?? 0 }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm bg-success text-white border-0">
<div class="card-body">
<h6>Delivered</h6>
<h3>{{ $stats['delivered'] ?? 0 }}</h3>
</div>
</div>
</div>

</div>


<div class="card shadow-sm border-0">

<div class="card-header bg-white">

<form method="GET" class="row g-2">

<div class="col-md-4">
<input type="text" name="search" value="{{ request('search') }}"
placeholder="Search Order Number..." class="form-control"/>
</div>

<div class="col-md-3">
<select name="status" class="form-select">
<option value="">All Status</option>
<option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
<option value="processing" {{ request('status')=='processing'?'selected':'' }}>Processing</option>
<option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
<option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
</select>
</div>

<div class="col-md-2">
<button class="btn btn-primary w-100">Search</button>
</div>

</form>

</div>


<div class="card-body p-0">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">
<tr>
<th>Order</th>
<th>Customer</th>
<th>Date</th>
<th>Status</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@forelse($orders as $order)

<tr>

<td class="fw-bold">
{{ $order->order_number ?? 'N/A' }}
</td>

<td>
{{ optional($order->user)->name ?? 'Guest' }}
</td>

<td>
{{ optional($order->created_at)->format('d M Y') }}
</td>

<td>
<span class="badge px-3 py-2
@if($order->status=='pending') bg-warning text-dark
@elseif($order->status=='processing') bg-info text-dark
@elseif($order->status=='shipped') bg-primary text-white
@elseif($order->status=='delivered') bg-success text-white
@elseif($order->status=='confirmed') bg-secondary text-white
@elseif($order->status=='cancelled') bg-danger text-white
@else bg-secondary text-white
@endif
" style="font-weight: 500; border-radius: 20px;">
{{ ucfirst($order->status ?? 'N/A') }}
</span>
</td>

<td class="fw-semibold">
₹{{ number_format($order->total ?? 0,2) }}
</td>

<td>

<a href="{{ route('admin.orders.show', $order->id) }}"
class="btn btn-sm btn-dark">
View
</a>

<a href="{{ route('admin.orders.invoice', $order->id) }}"
target="_blank"
class="btn btn-sm btn-success">
Invoice
</a>

</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-4">
No Orders Found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<div class="card-footer">
{{ $orders->links() }}
</div>

</div>

</div>

@endsection