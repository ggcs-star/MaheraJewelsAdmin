@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

<div class="container-fluid py-4">

<div class="card border-0 shadow-lg" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom py-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fas fa-eye text-primary me-2"></i>Coupon Details
                </h4>
                <p class="text-muted mb-0">View complete coupon information</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-ticket-alt me-1"></i> {{ $coupon->code }}
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="p-4 rounded-3 bg-gradient-to-r from-primary/10 to-primary/5 border border-primary/20 h-100">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary/20 rounded-circle p-2 me-2">
                            <i class="fas fa-circle-check text-primary"></i>
                        </div>
                        <span class="text-muted small">Status</span>
                    </div>
                    <h5 class="mb-0">
                        <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 rounded-3 bg-gradient-to-r from-info/10 to-info/5 border border-info/20 h-100">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info/20 rounded-circle p-2 me-2">
                            <i class="fas fa-tags text-info"></i>
                        </div>
                        <span class="text-muted small">Coupon Type</span>
                    </div>
                    <h5 class="mb-0">
                        <span class="badge {{ $coupon->coupon_type === 'BANK' ? 'bg-info' : 'bg-secondary' }} px-3 py-2">
                            {{ $coupon->coupon_type }}
                        </span>
                    </h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 rounded-3 bg-gradient-to-r from-warning/10 to-warning/5 border border-warning/20 h-100">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning/20 rounded-circle p-2 me-2">
                            <i class="fas fa-percent text-warning"></i>
                        </div>
                        <span class="text-muted small">Discount</span>
                    </div>
                    <h5 class="mb-0 text-warning">
                        {{ $coupon->discount_type === 'PERCENT' ? $coupon->value . '%' : '₹' . number_format($coupon->value) }}
                    </h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 rounded-3 bg-gradient-to-r from-success/10 to-success/5 border border-success/20 h-100">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success/20 rounded-circle p-2 me-2">
                            <i class="fas fa-chart-simple text-success"></i>
                        </div>
                        <span class="text-muted small">Usage</span>
                    </div>
                    <h5 class="mb-0 text-success">{{ $coupon->used_count }} / {{ $coupon->usage_limit }}</h5>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ ($coupon->used_count / max(1,$coupon->usage_limit)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>Coupon Overview
                        </h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary/10 rounded-circle p-4 d-inline-flex mx-auto mb-3">
                            <i class="fas fa-ticket-alt text-primary fa-3x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $coupon->name }}</h5>
                        <div class="bg-light rounded-3 p-2 mb-3">
                            <code class="text-primary fw-bold fs-5">{{ $coupon->code }}</code>
                        </div>
                        <p class="text-muted small">{{ $coupon->description ?? 'No description provided.' }}</p>
                        
                        @if($coupon->coupon_type === 'BANK')
                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-center gap-3">
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-university me-1"></i> {{ $coupon->bank?->name ?? 'No Bank' }}
                                    </span>
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-credit-card me-1"></i> {{ ucfirst($coupon->card_type) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="border-top pt-3 mt-3">
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i> Created: {{ $coupon->created_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-4">

                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-cog text-primary me-2"></i>Generation Details
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Generate Type</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-{{ ($coupon->generate_type ?? 'single') === 'bulk' ? 'layer-group' : 'tag' }} text-primary me-1"></i>
                                                {{ ucfirst($coupon->generate_type ?? 'Single') }}
                                            </div>
                                        </div>
                                    </div>
                                    @if(($coupon->generate_type ?? 'single') === 'bulk')
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small mb-1">Campaign Name</div>
                                                <div class="fw-bold">
                                                    <i class="fas fa-flag text-primary me-1"></i>
                                                    {{ $coupon->campaign_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small mb-1">Quantity</div>
                                                <div class="fw-bold">
                                                    <i class="fas fa-sort-numeric-up text-primary me-1"></i>
                                                    {{ $coupon->quantity ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-sliders-h text-warning me-2"></i>Rules & Limitations
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Minimum Order</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-shopping-cart text-primary me-1"></i>
                                                ₹{{ number_format($coupon->min_order_amount) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Max Discount</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-chart-line text-primary me-1"></i>
                                                {{ $coupon->max_discount ? '₹'.number_format($coupon->max_discount) : 'No limit' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Platforms</div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($coupon->platforms as $platform)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                        <i class="fas fa-check-circle me-1"></i> {{ $platform->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-box text-primary me-2"></i>Product Restrictions
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Category</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-folder text-primary me-1"></i>
                                                {{ $coupon->category?->name ?? 'All Categories' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Sub Category</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-folder-open text-primary me-1"></i>
                                                {{ $coupon->subcategory?->name ?? 'All' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Product</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-cube text-primary me-1"></i>
                                                {{ $coupon->product?->name ?? 'All Products' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">One Time Per User</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-user-check text-primary me-1"></i>
                                                {{ $coupon->one_time_per_user ? 'Yes' : 'No' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-calendar-alt text-info me-2"></i>Validity Period
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Start Date</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-calendar-day text-primary me-1"></i>
                                                {{ $coupon->starts_at->format('d M Y') }}
                                                <small class="text-muted ms-2">{{ $coupon->starts_at->format('h:i A') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Expiry Date</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                                {{ $coupon->expires_at->format('d M Y') }}
                                                <small class="text-muted ms-2">{{ $coupon->expires_at->format('h:i A') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @php
                                    $now = now();
                                    $isExpired = $now->greaterThan($coupon->expires_at);
                                    $isUpcoming = $now->lessThan($coupon->starts_at);
                                    $isActive = !$isExpired && !$isUpcoming;
                                @endphp
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $isActive ? 'bg-success' : ($isExpired ? 'bg-danger' : 'bg-warning') }} px-3 py-2">
                                            <i class="fas fa-{{ $isActive ? 'check-circle' : ($isExpired ? 'times-circle' : 'clock') }} me-1"></i>
                                            {{ $isActive ? 'Currently Active' : ($isExpired ? 'Expired' : 'Upcoming') }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $isActive 
                                                ? 'Expires in ' . $coupon->expires_at->diffForHumans()
                                                : ($isExpired 
                                                    ? 'Expired ' . $coupon->expires_at->diffForHumans()
                                                    : 'Starts ' . $coupon->starts_at->diffForHumans())
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fas fa-percent text-success me-2"></i>Discount Information
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Discount Type</div>
                                            <div class="fw-bold">
                                                <i class="fas fa-{{ $coupon->discount_type === 'PERCENT' ? 'percentage' : 'coins' }} text-primary me-1"></i>
                                                {{ $coupon->discount_type === 'PERCENT' ? 'Percentage Discount' : 'Fixed Amount' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="text-muted small mb-1">Discount Value</div>
                                            <div class="fw-bold text-success">
                                                <i class="fas fa-tag text-success me-1"></i>
                                                {{ $coupon->discount_type === 'PERCENT' ? $coupon->value . '% OFF' : '₹' . number_format($coupon->value) . ' OFF' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="border-top pt-4 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ admin_route('coupons.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ admin_route('coupons.edit', $coupon) }}" class="btn btn-primary px-4 fw-semibold">
                        <i class="fas fa-edit me-2"></i> Edit Coupon
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</div>
@endsection