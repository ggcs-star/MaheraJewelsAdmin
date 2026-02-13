@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-user text-primary me-2"></i> Customer Details
        </h4>
        <small class="text-muted">View complete customer information</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ admin_route('customers.edit', $customer) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ admin_route('customers.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>
</div>

<div class="row g-4">

{{-- ================= BASIC INFO ================= --}}
<div class="col-lg-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
            <div class="mb-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                            d-flex align-items-center justify-content-center mx-auto"
                     style="width:90px;height:90px;font-size:36px;">
                    {{ strtoupper(substr($customer->name,0,1)) }}
                </div>
            </div>

            <h5 class="fw-bold mb-1">{{ $customer->name }}</h5>

            <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }}">
                <i class="fas {{ $customer->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                {{ $customer->is_active ? 'Active' : 'Inactive' }}
            </span>

            <div class="mt-4 text-start">
                @if($customer->email)
                <div class="mb-2">
                    <i class="fas fa-envelope me-2 text-muted"></i>{{ $customer->email }}
                </div>
                @endif

                @if($customer->mobile)
                <div>
                    <i class="fas fa-phone me-2 text-muted"></i>{{ $customer->mobile }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ================= ADDRESS ================= --}}
<div class="col-lg-8">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            <i class="fas fa-map-marker-alt me-2 text-primary"></i> Address Information
        </div>
        <div class="card-body">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small">Address Line 1</label>
                    <div class="fw-semibold">{{ $customer->address_line_1 ?? '—' }}</div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">Address Line 2</label>
                    <div class="fw-semibold">{{ $customer->address_line_2 ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="text-muted small">City</label>
                    <div class="fw-semibold">{{ $customer->city ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="text-muted small">State</label>
                    <div class="fw-semibold">{{ $customer->state ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="text-muted small">Country</label>
                    <div class="fw-semibold">{{ $customer->country ?? '—' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="text-muted small">Zip Code</label>
                    <div class="fw-semibold">{{ $customer->zip_code ?? '—' }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

</div>
</div>
@endsection
