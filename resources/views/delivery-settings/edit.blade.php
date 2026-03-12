@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2 class="fw-bold mb-0">Edit Delivery Setting</h2>
</div>

<a href="{{ admin_route('delivery-settings.index') }}"
class="btn btn-outline-secondary">
Back
</a>

</div>

@if ($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST"
action="{{ admin_route('delivery-settings.update',$deliverySetting->id) }}">

@csrf
@method('PUT')

<div class="card shadow-sm">

<div class="card-body">

<div class="row g-4">

<div class="col-md-3">
<label class="form-label fw-semibold">Delivery Fee</label>
<input type="number"
step="0.01"
name="delivery_fee"
class="form-control"
value="{{ old('delivery_fee',$deliverySetting->delivery_fee) }}">
</div>

<div class="col-md-3">
<label class="form-label fw-semibold">Platform Fee</label>
<input type="number"
step="0.01"
name="platform_fee"
class="form-control"
value="{{ old('platform_fee',$deliverySetting->platform_fee) }}">
</div>

<div class="col-md-3">
<label class="form-label fw-semibold">Tax %</label>
<input type="number"
step="0.01"
name="tax_percent"
class="form-control"
value="{{ old('tax_percent',$deliverySetting->tax_percent) }}">
</div>

<div class="col-md-3">
<label class="form-label fw-semibold">Free Delivery Above</label>
<input type="number"
step="0.01"
name="free_delivery_above"
class="form-control"
value="{{ old('free_delivery_above',$deliverySetting->free_delivery_above) }}">
</div>

</div>

</div>

<div class="card-footer text-end">

<button class="btn btn-primary">
Update
</button>

</div>

</div>

</form>

</div>

@endsection