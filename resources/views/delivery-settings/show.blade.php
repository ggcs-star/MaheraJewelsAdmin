@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2 class="fw-bold mb-0">Delivery Setting Details</h2>
</div>

<a href="{{ admin_route('delivery-settings.index') }}"
class="btn btn-outline-secondary">
Back
</a>

</div>

<div class="card shadow-sm">

<div class="card-body">

<table class="table">

<tr>
<th width="200">Delivery Fee</th>
<td>₹{{ $deliverySetting->delivery_fee }}</td>
</tr>

<tr>
<th>Platform Fee</th>
<td>₹{{ $deliverySetting->platform_fee }}</td>
</tr>

<tr>
<th>Tax</th>
<td>{{ $deliverySetting->tax_percent }}%</td>
</tr>

<tr>
<th>Free Delivery Above</th>
<td>₹{{ $deliverySetting->free_delivery_above }}</td>
</tr>

</table>

</div>

</div>

</div>

@endsection