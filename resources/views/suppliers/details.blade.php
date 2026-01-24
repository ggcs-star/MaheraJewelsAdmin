@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('suppliers.index') }}">Suppliers</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1">Supplier Profile</h2>
            <div class="text-muted">Supplier ID: #{{ str_pad($supplier->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('suppliers.edit', $supplier) }}" class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i>Edit Supplier
            </a>
            <a href="{{ admin_route('suppliers.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    {{-- QUICK STATS CARDS WITH ICONS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-industry text-primary fa-lg"></i>
                        </div>
                        <div class="text-muted small">Supplier Type</div>
                    </div>
                    <div class="fw-bold fs-5 text-capitalize">{{ $supplier->type }}</div>
                    <div class="small text-muted mt-1">Manufacturer/Supplier</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-percentage text-success fa-lg"></i>
                        </div>
                        <div class="text-muted small">Commission</div>
                    </div>
                    <div class="fw-bold fs-5">
                        @if ($supplier->commission_type === 'percentage')
                            {{ $supplier->commission_value }}%
                        @else
                            ₹{{ number_format($supplier->commission_value, 2) }}
                        @endif
                    </div>
                    <div class="small text-muted mt-1">{{ ucfirst($supplier->commission_type) }} based</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-calendar-alt text-warning fa-lg"></i>
                        </div>
                        <div class="text-muted small">Payment Terms</div>
                    </div>
                    <div class="fw-bold fs-5">{{ $supplier->payment_terms ?? '—' }}</div>
                    <div class="small text-muted mt-1">Payment duration</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-{{ $supplier->status === 'active' ? 'check-circle' : 'pause-circle' }} text-{{ $supplier->status === 'active' ? 'success' : 'secondary' }} fa-lg"></i>
                        </div>
                        <div class="text-muted small">Status</div>
                    </div>
                    <div class="fw-bold fs-5 text-capitalize">{{ $supplier->status }}</div>
                    <div class="small text-muted mt-1">Account status</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- LEFT PROFILE CARD --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        Supplier Details
                    </h5>
                </div>
                <div class="card-body text-center pt-0">

                    <div class="position-relative d-inline-block mb-3">
                        <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width: 100px; height: 100px; font-size: 36px; font-weight: 600;">
                            {{ strtoupper(substr($supplier->name,0,1)) }}
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                            <div class="rounded-circle bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}"
                                 style="width: 16px; height: 16px;"></div>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-1">{{ $supplier->name }}</h4>
                    <div class="text-muted mb-3">
                        <i class="fas fa-building me-1"></i>{{ $supplier->company_name ?? 'Individual Supplier' }}
                    </div>

                    <div class="mb-4">
                        <span class="badge rounded-pill px-4 py-2 fs-6
                            {{ $supplier->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            <i class="fas fa-{{ $supplier->status === 'active' ? 'check' : 'pause' }} me-1"></i>
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </div>

                    {{-- CONTACT INFO --}}
                    <div class="border-top pt-4 mt-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Phone</div>
                                <div class="fw-semibold">{{ $supplier->phone }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Email</div>
                                <div class="fw-semibold">{{ $supplier->email ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT DETAILS --}}
        <div class="col-xl-8 col-lg-8">
            <div class="row g-4">

                {{-- ADDRESS CARD --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                Address Information
                            </h6>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-location-dot me-1"></i>Location
                            </span>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-home text-muted me-2 small"></i>
                                            <div class="text-muted small">Address</div>
                                        </div>
                                        <div class="fw-semibold">{{ $supplier->address ?? '—' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-city text-muted me-2 small"></i>
                                            <div class="text-muted small">City</div>
                                        </div>
                                        <div class="fw-semibold">{{ $supplier->city ?? '—' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-landmark text-muted me-2 small"></i>
                                            <div class="text-muted small">State</div>
                                        </div>
                                        <div class="fw-semibold">{{ $supplier->state ?? '—' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-globe text-muted me-2 small"></i>
                                            <div class="text-muted small">Country</div>
                                        </div>
                                        <div class="fw-semibold">{{ $supplier->country ?? '—' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-mail-bulk text-muted me-2 small"></i>
                                            <div class="text-muted small">Pincode</div>
                                        </div>
                                        <div class="fw-semibold">{{ $supplier->pincode ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAX & COMMISSION ROW --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-file-invoice text-primary me-2"></i>
                                Tax Information
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-receipt text-muted me-2 small"></i>
                                        <span class="text-muted small">GST Number</span>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary small">
                                        <i class="fas fa-file-contract me-1"></i>GST
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-primary ps-3">
                                    {{ $supplier->gst_number ?? '—' }}
                                </div>
                            </div>

                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-id-card text-muted me-2 small"></i>
                                        <span class="text-muted small">PAN Number</span>
                                    </div>
                                    <span class="badge bg-warning bg-opacity-10 text-warning small">
                                        <i class="fas fa-file-signature me-1"></i>PAN
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-warning ps-3">
                                    {{ $supplier->pan_number ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-handshake text-primary me-2"></i>
                                Agreement Details
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-tag text-muted me-2 small"></i>
                                            <div class="text-muted small">Commission Type</div>
                                        </div>
                                        <div class="fw-semibold text-capitalize">
                                            <i class="fas fa-{{ $supplier->commission_type === 'percentage' ? 'percent' : 'rupee-sign' }} me-1"></i>
                                            {{ $supplier->commission_type }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-money-bill-wave text-muted me-2 small"></i>
                                            <div class="text-muted small">Commission Value</div>
                                        </div>
                                        <div class="fw-semibold">
                                            @if ($supplier->commission_type === 'percentage')
                                                <span class="badge bg-success text-white">
                                                    <i class="fas fa-percent me-1"></i>{{ $supplier->commission_value }}%
                                                </span>
                                            @else
                                                <span class="badge bg-info text-white">
                                                    <i class="fas fa-rupee-sign me-1"></i>{{ number_format($supplier->commission_value, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-calendar-check text-muted me-2 small"></i>
                                            <div class="text-muted small">Payment Terms</div>
                                        </div>
                                        <div class="fw-semibold">
                                            <i class="fas fa-clock me-1 text-primary"></i>
                                            {{ $supplier->payment_terms ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NOTES --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-sticky-note text-primary me-2"></i>
                                Internal Notes
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-4 border rounded bg-light bg-opacity-50">
                                @if($supplier->notes)
                                    <div class="d-flex">
                                        <i class="fas fa-quote-left text-muted me-2 mt-1"></i>
                                        <div class="fst-italic">{{ $supplier->notes }}</div>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle fa-lg me-2"></i>
                                        No notes added for this supplier
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    border-color: #e0e0e0;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.border-start {
    border-left-width: 4px !important;
}
.small {
    font-size: 0.85rem;
}
</style>
@endsection