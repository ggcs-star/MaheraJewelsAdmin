@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Supplier</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ admin_route('suppliers.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Supplier Type</label>
                <select name="type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="manufacturer">Manufacturer</option>
                    <option value="distributor">Distributor</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Commission Type</label>
                <select name="commission_type" class="form-control" required>
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed (₹)</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Commission Value</label>
                <input type="number" step="0.01" name="commission_value" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Payment Terms</label>
                <input type="text" name="payment_terms" class="form-control" placeholder="Net 15 / Net 30">
            </div>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="2"></textarea>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>City</label>
                <input type="text" name="city" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>State</label>
                <input type="text" name="state" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Country</label>
                <input type="text" name="country" value="India" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Pincode</label>
                <input type="text" name="pincode" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>GST Number</label>
                <input type="text" name="gst_number" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>PAN Number</label>
                <input type="text" name="pan_number" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
        </div>

        <button class="btn btn-success">Save Supplier</button>
        <a href="{{ admin_route('suppliers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@endsection