@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
@endpush

<div class="container-fluid py-4">

<div class="card border-0 shadow-lg" style="border-radius: 10px; overflow: hidden;">
    <!-- Card Header -->
    <div class="card-header bg-white border-bottom py-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Coupon
                </h4>
                <p class="text-muted mb-0">Update coupon details and configuration</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-pencil-alt me-1"></i> Editing Mode
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <!-- ================= SECTION 1: PLATFORMS ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">
<i class="fas fa-desktop text-primary me-2"></i>
Platform Selection <span class="text-danger">*</span>
                </h6>
                <p class="text-muted mb-3">Select platforms where this coupon will be valid</p>
            </div>

            <div class="row g-4">
                @foreach($platforms->take(3) as $platform)
                    @php
                        $checked = $coupon->platforms->contains($platform->id);
                        $platform_lower = strtolower($platform->name);
                    @endphp
                    
                    <div class="col-md-4">
                        <label class="d-block cursor-pointer m-0">
                            <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}" {{ $checked ? 'checked' : '' }} hidden>
                            
                            <div class="border rounded-3 p-4 h-100 transition-all {{ $checked ? 'border-primary border-2 bg-primary-light' : 'border-light' }}">
                                <div class="d-flex align-items-center mb-3">
                                    <!-- Platform Icon - Same as Create form -->
                                    @if($platform->icon && file_exists(public_path($platform->icon)))
                                        <div class="me-3">
                                            <img src="{{ asset($platform->icon) }}" 
                                                 alt="{{ $platform->name }} icon" 
                                                 height="32"
                                                 class="img-fluid">
                                        </div>
                                    @else
                                       <div class="me-3">

    {{-- AMAZON --}}
    @if(str_contains($platform_lower, 'amazon'))
        <svg viewBox="0 0 200 60" style="height:32px" preserveAspectRatio="xMidYMid meet">
            <text x="0" y="42"
                  font-size="38"
                  font-weight="700"
                  fill="#111"
                  font-family="Arial, Helvetica, sans-serif">
                amazon
            </text>
            <path d="M10 50 C40 70, 120 70, 150 50"
                  stroke="#FF9900"
                  stroke-width="5"
                  fill="none"
                  stroke-linecap="round"/>
        </svg>

    {{-- FLIPKART --}}
    @elseif(str_contains($platform_lower, 'flipkart'))
        <svg viewBox="0 0 64 64" style="height:32px" preserveAspectRatio="xMidYMid meet">
            <rect width="64" height="64" rx="14" fill="#2874F0"/>
            <text x="32" y="44"
                  text-anchor="middle"
                  font-size="40"
                  font-weight="800"
                  fill="#FFD700"
                  font-family="Arial, Helvetica, sans-serif">
                F
            </text>
        </svg>

    {{-- WEBSITE / OWN --}}
    @elseif(str_contains($platform_lower, 'own') || str_contains($platform_lower, 'website'))
        <span style="font-size: 1.8rem;">🌐</span>

    {{-- DEFAULT --}}
    @else
        <span style="font-size: 1.6rem;">🏬</span>
    @endif

</div>

                                    @endif
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-0">{{ $platform->name }}</h6>
                                        <span class="badge {{ $checked ? 'bg-primary text-white' : 'bg-light text-dark' }} rounded-pill small px-2 py-1">
                                            {{ $checked ? 'Selected' : 'Select' }}
                                        </span>
                                    </div>
                                    
                                    @if($checked)
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-primary fs-5"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $checked ? 'Selected for this coupon' : 'Click to select this platform' }}
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="my-5">

        <!-- ================= SECTION 2: BASIC INFORMATION ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>Basic Information
                </h6>
            </div>

            <div class="row g-4">
                <!-- Coupon Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-tag text-primary me-1"></i>Coupon Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="coupon_name" 
                               class="form-control"
                               value="{{ old('coupon_name', $coupon->name) }}"
                               required>
                        <div class="form-text mt-2">
                            <i class="fas fa-lightbulb text-primary me-1"></i> A descriptive name for this coupon
                        </div>
                    </div>
                </div>

                <!-- Coupon Code -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-hashtag text-primary me-1"></i>Coupon Code
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-hashtag text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="code" 
                                   class="form-control border-start-0 bg-light"
                                   value="{{ old('code', $coupon->code) }}"
                                   disabled>
                        </div>
                        <div class="alert alert-warning mt-2 py-2 px-3" style="font-size: 0.875rem;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="fw-medium">Coupon code cannot be modified</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-align-left text-primary me-1"></i>Description
                        </label>
                        <textarea name="coupon_description" 
                                  class="form-control"
                                  rows="3">{{ old('coupon_description', $coupon->description) }}</textarea>
                        <div class="form-text mt-2">
                            <i class="fas fa-comment-dots text-primary me-1"></i> Optional details about this coupon offer
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 3: DISCOUNT SETTINGS ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-percentage text-success me-2"></i>Discount Settings
                </h6>
            </div>

            <div class="row g-4">
                <!-- Coupon Type -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-gift text-primary me-1"></i>Coupon Type <span class="text-danger">*</span>
                        </label>
                        <select name="coupon_type" id="coupon_type" class="form-select" required>
                            <option value="NORMAL" {{ $coupon->coupon_type === 'NORMAL' ? 'selected' : '' }}>Coupon</option>
                            <option value="BANK" {{ $coupon->coupon_type === 'BANK' ? 'selected' : '' }}>Bank Offer</option>
                        </select>
                        <div class="form-text mt-2">
                            <i class="fas fa-question-circle text-primary me-1"></i> Select the type of coupon
                        </div>
                    </div>
                </div>

                <!-- Discount Type -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-calculator text-primary me-1"></i>Discount Type <span class="text-danger">*</span>
                        </label>
                        <select name="discount_type" class="form-select" required>
                            <option value="FLAT" {{ $coupon->discount_type === 'FLAT' ? 'selected' : '' }}>Flat Amount</option>
                            <option value="PERCENT" {{ $coupon->discount_type === 'PERCENT' ? 'selected' : '' }}>Percentage</option>
                        </select>
                        <div class="form-text mt-2">
                            <i class="fas fa-chart-line text-primary me-1"></i> How the discount is calculated
                        </div>
                    </div>
                </div>

                <!-- Discount Value -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-rupee-sign text-primary me-1"></i>Discount Value <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   name="value" 
                                   class="form-control"
                                   value="{{ old('value', $coupon->value) }}"
                                   required
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                            <span class="input-group-text bg-light">₹</span>
                        </div>
                        <div class="form-text mt-2">
                            <i class="fas fa-money-bill-wave text-primary me-1"></i> The discount amount or percentage
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 4: RULES & LIMITATIONS ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-sliders-h text-warning me-2"></i>Rules & Limitations
                </h6>
            </div>

            <div class="row g-4">
                <!-- Minimum Order Amount -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
<i class="fas fa-shopping-cart text-primary me-1"></i>
Minimum Order Amount <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" 
                                   name="min_order_amount" 
                                   class="form-control"
                                   value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        <div class="form-text mt-2">
                            Minimum cart value to use this coupon
                        </div>
                    </div>
                </div>

                <!-- Max Discount -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-chart-line text-primary me-1"></i>Max Discount
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" 
                                   name="max_discount" 
                                   class="form-control"
                                   value="{{ old('max_discount', $coupon->max_discount) }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="No limit">
                        </div>
                        <div class="form-text mt-2">
                            Maximum discount applicable
                        </div>
                    </div>
                </div>

                <!-- Usage Limit -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
<i class="fas fa-users text-primary me-1"></i>
Usage Limit <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               name="usage_limit" 
                               class="form-control"
                               value="{{ old('usage_limit', $coupon->usage_limit) }}"
                               min="0"
                               placeholder="Unlimited">
                        <div class="form-text mt-2">
                            Maximum number of times this coupon can be used
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 5: VALIDITY PERIOD ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-calendar-alt text-info me-2"></i>Validity Period
                </h6>
            </div>

            <div class="row g-4">
                <!-- Start Date -->
                <div class="col-md-6">
                    <div class="form-group">
                       <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-calendar-day text-muted me-1"></i>
                        Start Date <span class="text-danger">*</span>
                    </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="date" 
                                   name="starts_at" 
                                   class="form-control"
                                   value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}">
                        </div>
                        <div class="form-text mt-2">
                            When the coupon becomes valid
                        </div>
                    </div>
                </div>

                <!-- Expiry Date -->
                <div class="col-md-6">
                    <div class="form-group">
                    <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-calendar-times text-muted me-1"></i>
                            Expiry Date <span class="text-danger">*</span>
                    </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="date" 
                                   name="expires_at" 
                                   class="form-control"
                                   value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                                   min="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}">
                        </div>
                        <div class="form-text mt-2">
                            When the coupon expires
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 6: BANK OFFER FIELDS ================= -->
        <div class="bank-field {{ $coupon->coupon_type === 'BANK' ? '' : 'd-none' }} mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-university text-primary me-2"></i>Bank Offer Details
                </h6>
            </div>

            @php
                $bank = $coupon->bank;
            @endphp

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-bank text-primary me-1"></i>Bank
                        </label>
                        <select name="bank_id" class="form-select">
                            <option value="">Select Bank</option>
                            @foreach($banks as $b)
                                <option value="{{ $b->id }}" {{ $bank && $bank->id == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2">
                            Select bank for this offer
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-credit-card text-primary me-1"></i>Card Type
                        </label>
                        <select name="card_type" class="form-select">
                            <option value="credit" {{ $coupon->card_type === 'credit' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit" {{ $coupon->card_type === 'debit' ? 'selected' : '' }}>Debit Card</option>
                            <option value="both" {{ $coupon->card_type === 'both' ? 'selected' : '' }}>EMI</option>
                        </select>
                        <div class="form-text mt-2">
                            Type of cards eligible for this offer
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 7: STATUS ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-toggle-on text-success me-2"></i>Status
                </h6>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" {{ $coupon->is_active ? 'selected' : '' }} class="text-success">
                                <i class="fas fa-check-circle me-1"></i> Active
                            </option>
                            <option value="0" {{ !$coupon->is_active ? 'selected' : '' }} class="text-danger">
                                <i class="fas fa-times-circle me-1"></i> Inactive
                            </option>
                        </select>
                        <div class="form-text mt-2">
                            Set coupon availability
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= FORM ACTIONS ================= -->
        <div class="border-top pt-5 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light px-4">
                         Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="fas fa-save me-2"></i> Update Coupon
                    </button>
                </div>
            </div>
        </div>

        </form>
    </div>
</div>

</div>



@endsection