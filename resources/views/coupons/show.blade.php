@extends('layouts.admin')

@section('content')
<div class="container-fluid coupon-details-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ admin_route('dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ admin_route('coupons.index') }}" class="text-decoration-none">
                            <i class="fas fa-tags me-1"></i>Coupons
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary">
                        <i class="fas fa-ticket-alt me-1"></i>{{ $coupon->code }}
                    </li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-1 text-dark">Coupon Details</h2>
            <div class="text-muted fs-6">
                <i class="fas fa-hashtag me-1"></i>Code: 
                <span class="fw-semibold text-primary">{{ $coupon->code }}</span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('coupons.edit', $coupon) }}" class="btn btn-primary px-4 btn-lg">
                <i class="fas fa-edit me-2"></i>Edit Coupon
            </a>
            <a href="{{ admin_route('coupons.index') }}" class="btn btn-light px-4 btn-lg border">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    {{-- TOP METRICS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-5 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-power-off text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Status</div>
                            <span class="badge rounded-pill px-3 py-2 fw-normal
                                {{ $coupon->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                <i class="fas fa-circle me-1 small"></i>
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-5 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-star text-info fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Coupon Type</div>
                            <span class="badge rounded-pill px-3 py-2 fw-normal
                                {{ $coupon->coupon_type === 'BANK' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}">
                                <i class="fas fa-{{ $coupon->coupon_type === 'BANK' ? 'university' : 'tag' }} me-1"></i>
                                {{ $coupon->coupon_type }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-5 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-percent text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Discount Value</div>
                            <div class="fw-bold fs-4 text-warning">
                                {{ $coupon->discount_type === 'PERCENT'
                                    ? $coupon->value . '%'
                                    : '₹' . number_format($coupon->value) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-5 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-chart-line text-success fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small mb-1">Usage Statistics</div>
                            <div class="fw-bold fs-4 text-success">
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                            </div>
                            <div class="progress mt-2" style="height:6px;">
                                <div class="progress-bar bg-success rounded"
                                     style="width: {{ ($coupon->used_count / max(1,$coupon->usage_limit)) * 100 }}%">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                {{ number_format(($coupon->used_count / max(1,$coupon->usage_limit)) * 100, 1) }}% utilized
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        {{-- LEFT OVERVIEW --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Coupon Overview
                    </h5>
                </div>

                <div class="card-body text-center pt-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                d-flex align-items-center justify-content-center mx-auto mb-4"
                         style="width: 100px; height: 100px; font-size: 40px; border: 3px dashed rgba(13, 110, 253, 0.3)">
                        <i class="fas fa-ticket-alt"></i>
                    </div>

                    <h4 class="fw-bold mb-2 text-dark">{{ $coupon->name }}</h4>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary 
                                 px-4 py-2 mb-3 fs-6 rounded-pill">
                        <i class="fas fa-hashtag me-1"></i>{{ $coupon->code }}
                    </span>

                    <div class="bg-light rounded p-3 mt-3 mb-4">
                        <p class="text-muted mb-0">
                            {{ $coupon->description ?? 'No description provided.' }}
                        </p>
                    </div>

                    @if($coupon->coupon_type === 'BANK')
                        <div class="border-top pt-3 mt-3">
                            <div class="mb-2">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2">
                                    <i class="fas fa-university me-1"></i>
                                    {{ $coupon->bank?->name ?? 'No Bank' }}
                                </span>
                            </div>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2">
                                <i class="fas fa-credit-card me-1"></i>
                                Card Type: {{ ucfirst($coupon->card_type) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer bg-white border-0 pt-3">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Created: {{ $coupon->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        {{-- RIGHT DETAILS --}}
        <div class="col-lg-8">
            <div class="row g-4">

                {{-- ORDER RULES --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 fw-bold py-3">
                            <i class="fas fa-sliders-h text-primary me-2"></i>
                            Rules & Limits
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-shopping-cart me-1"></i>Minimum Order
                                        </div>
                                        <div class="fw-bold fs-5 text-dark">₹{{ number_format($coupon->min_order_amount) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-money-bill-wave me-1"></i>Max Discount
                                        </div>
                                        <div class="fw-bold fs-5 text-dark">
                                            {{ $coupon->max_discount ? '₹'.number_format($coupon->max_discount) : 'No limit' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 border rounded bg-light h-100">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-globe me-1"></i>Platforms
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($coupon->platforms as $platform)
                                                <span class="badge bg-white text-dark border px-3 py-2">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    {{ $platform->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VALIDITY --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 fw-bold py-3">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Validity Period
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-play-circle text-success me-1"></i>Start Date
                                        </div>
                                        <div class="fw-bold fs-5 text-success">
                                            {{ $coupon->starts_at->format('d M Y') }}
                                        </div>
                                        <small class="text-muted">{{ $coupon->starts_at->format('h:i A') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-stop-circle text-danger me-1"></i>Expiry Date
                                        </div>
                                        <div class="fw-bold fs-5 text-danger">
                                            {{ $coupon->expires_at->format('d M Y') }}
                                        </div>
                                        <small class="text-muted">{{ $coupon->expires_at->format('h:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- VALIDITY STATUS --}}
                            @php
                                $now = now();
                                $isExpired = $now->greaterThan($coupon->expires_at);
                                $isUpcoming = $now->lessThan($coupon->starts_at);
                                $isActive = !$isExpired && !$isUpcoming;
                            @endphp
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="badge rounded-pill px-3 py-2
                                            {{ $isActive ? 'bg-success' : ($isExpired ? 'bg-danger' : 'bg-warning') }}">
                                            <i class="fas fa-{{ $isActive ? 'check-circle' : ($isExpired ? 'times-circle' : 'clock') }} me-1"></i>
                                            {{ $isActive ? 'Currently Active' : ($isExpired ? 'Expired' : 'Upcoming') }}
                                        </span>
                                    </div>
                                    <div class="text-muted">
                                        <i class="fas fa-history me-1"></i>
                                        {{ $isActive 
                                            ? 'Expires in ' . $coupon->expires_at->diffForHumans()
                                            : ($isExpired 
                                                ? 'Expired ' . $coupon->expires_at->diffForHumans()
                                                : 'Starts ' . $coupon->starts_at->diffForHumans())
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DISCOUNT TYPE --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 fw-bold py-3">
                            <i class="fas fa-percent text-primary me-2"></i>
                            Discount Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">Discount Type</div>
                                        <div class="fw-bold fs-5 text-dark">
                                            <i class="fas fa-{{ $coupon->discount_type === 'PERCENT' ? 'percentage' : 'rupee-sign' }} me-2"></i>
                                            {{ $coupon->discount_type === 'PERCENT' ? 'Percentage Discount' : 'Fixed Amount' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 border rounded bg-light">
                                        <div class="text-muted small mb-2">Discount Value</div>
                                        <div class="fw-bold fs-5 text-primary">
                                            {{ $coupon->discount_type === 'PERCENT'
                                                ? $coupon->value . '% OFF'
                                                : '₹' . number_format($coupon->value) . ' OFF' }}
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
</div>
@endsection