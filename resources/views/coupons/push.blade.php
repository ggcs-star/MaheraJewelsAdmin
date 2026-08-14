@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateType = document.getElementById('generate_type');
    const couponCodeField = document.getElementById('coupon_code_field');
    const bulkFields = document.getElementById('bulk_fields');
    
    function toggleFields() {
        if (generateType.value === 'bulk') {
            couponCodeField.style.display = 'none';
            bulkFields.style.display = 'block';
        } else {
            couponCodeField.style.display = 'block';
            bulkFields.style.display = 'none';
        }
    }
    
    generateType.addEventListener('change', toggleFields);
    toggleFields(); // Run on page load
});
</script>
@endpush

<div class="container-fluid py-4">

<div class="card border-0 shadow-lg" style="border-radius: 10px;">
    <!-- Card Header -->
    <div class="card-header bg-white border-bottom py-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Coupon
                </h4>
                <p class="text-muted mb-0">Create a new coupon offer with detailed configuration</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-ticket-alt me-1"></i> New Coupon
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
<form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z"/>
            </svg>

            <h4 class="font-semibold text-red-700">
                Please fix the following errors:
            </h4>
        </div>

        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <!-- ================= SECTION: GENERATE TYPE ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-cog text-primary me-2"></i>Generate Type
                </h6>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-code-branch text-primary me-1"></i>Generate Type <span class="text-danger">*</span>
                        </label>
                        <select name="generate_type" 
                                id="generate_type" 
                                class="form-select @error('generate_type') is-invalid @enderror">
                            <option value="single" {{ old('generate_type', 'single') == 'single' ? 'selected' : '' }}>
                                Single Coupon
                            </option>
                            <option value="bulk" {{ old('generate_type') == 'bulk' ? 'selected' : '' }}>
                                Bulk Coupon
                            </option>
                        </select>
                        @error('generate_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle text-primary me-1"></i> Choose how to generate coupons
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- ================= SECTION 1: PLATFORMS ================= -->
        <div class="mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="fas fa-desktop text-primary me-2"></i>
                    Platform Selection <span class="text-danger">*</span>
                </h6>

                <p class="text-muted mb-3">Choose platforms where this coupon will be valid</p>
            </div>

            <div class="row g-4">
                @foreach($platforms->take(3) as $platform)
                    @php
                        $platform_lower = strtolower($platform->name);
                    @endphp
                    
                    <div class="col-md-4">
                        <label class="d-block cursor-pointer m-0">
                        <input type="checkbox"
                            name="platform_ids[]"
                            value="{{ $platform->id }}"
                            hidden
                            {{ in_array($platform->id, old('platform_ids', [])) ? 'checked' : '' }}>                            
                            <div class="border rounded-3 p-4 h-100 transition-all platform-card" 
                                 data-selected="false">
                                <div class="d-flex align-items-center mb-3">
                                    <!-- Platform Icon - Same as Edit form -->
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
            <text x="0" y="42" font-size="38" font-weight="700"
                  fill="#111" font-family="Arial, Helvetica, sans-serif">
                amazon
            </text>
            <path d="M10 50 C40 70, 120 70, 150 50"
                  stroke="#FF9900" stroke-width="5"
                  fill="none" stroke-linecap="round"/>
        </svg>

    {{-- FLIPKART --}}
    @elseif(str_contains($platform_lower, 'flipkart'))
        <svg viewBox="0 0 64 64" style="height:32px" preserveAspectRatio="xMidYMid meet">
            <rect width="64" height="64" rx="14" fill="#2874F0"/>
            <text x="32" y="44" text-anchor="middle"
                  font-size="40" font-weight="800"
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
                                        <span class="badge bg-light text-dark rounded-pill small px-2 py-1">Select</span>
                                    </div>
                                    
                                    <!-- Check Icon (Hidden by default) -->
                                    <div class="flex-shrink-0 check-icon d-none">
                                        <i class="fas fa-check-circle text-primary fs-5"></i>
                                    </div>
                                </div>
                                
                                <div class="text-muted small mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Click to select this platform
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
       value="{{ old('coupon_name') }}"
       class="form-control @error('coupon_name') is-invalid @enderror"
       placeholder="e.g., Summer Sale Discount">

@error('coupon_name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

                        <div class="form-text mt-2">
                            <i class="fas fa-lightbulb text-primary me-1"></i> A descriptive name for this coupon
                        </div>
                    </div>
                </div>

                <!-- Coupon Code (Single) - Hidden for Bulk -->
                <div class="col-md-6" id="coupon_code_field">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-hashtag text-primary me-1"></i>Coupon Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
       name="code"
       value="{{ old('code') }}"
       class="form-control @error('code') is-invalid @enderror"
       placeholder="e.g., SUMMER25">

@error('code')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

                        <div class="form-text mt-2">
                            <i class="fas fa-key text-primary me-1"></i> Unique code customers will enter at checkout
                        </div>
                    </div>
                </div>

                <!-- Bulk Fields -->
                <div id="bulk_fields" style="display: none;">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-flag text-primary me-1"></i>Campaign Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="campaign_name"
                                       value="{{ old('campaign_name') }}"
                                       class="form-control @error('campaign_name') is-invalid @enderror"
                                       placeholder="e.g., Summer Sale 2024">
                                @error('campaign_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-font text-primary me-1"></i>Coupon Prefix <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="prefix"
                                       value="{{ old('prefix') }}"
                                       class="form-control @error('prefix') is-invalid @enderror"
                                       placeholder="e.g., SALE">
                                @error('prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-sort-numeric-up text-primary me-1"></i>Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="quantity"
                                       value="{{ old('quantity') }}"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       placeholder="e.g., 100"
                                       min="1"
                                       max="1000">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-align-left text-primary me-1"></i>Description
                        </label>

                        <textarea
name="coupon_description"
class="form-control"
rows="3">{{ old('coupon_description') }}</textarea>
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
                        <select name="coupon_type"
                                id="coupon_type"
                                class="form-select @error('coupon_type') is-invalid @enderror">
                            <option value="">Select type</option>
                            <option value="NORMAL" {{ old('coupon_type') === 'NORMAL' ? 'selected' : '' }}>
                                Coupon
                            </option>
                            <option value="BANK" {{ old('coupon_type') === 'BANK' ? 'selected' : '' }}>
                                Bank Offer
                            </option>
                        </select>

                    @error('coupon_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

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
                        <select name="discount_type"
        class="form-select @error('discount_type') is-invalid @enderror">
    <option value="">Select discount type</option>
    <option value="FLAT" {{ old('discount_type') === 'FLAT' ? 'selected' : '' }}>
        Flat Amount
    </option>
    <option value="PERCENT" {{ old('discount_type') === 'PERCENT' ? 'selected' : '' }}>
        Percentage
    </option>
</select>

@error('discount_type')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

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
           value="{{ old('value') }}"
           class="form-control @error('value') is-invalid @enderror"
           placeholder="0.00"
           step="0.01"
           min="0">
    <span class="input-group-text bg-light">₹</span>
</div>

@error('value')
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

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
                            Minimum Order Amount 
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                           <input type="number"
                                name="min_order_amount"
                                value="{{ old('min_order_amount') }}"
                                class="form-control @error('min_order_amount') is-invalid @enderror"
                                placeholder="0.00"
                                step="0.01"
                                min="0">

                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

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
                                   placeholder="No limit"
                                   step="0.01"
                                   min="0">
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
                            <i class="fas fa-shopping-cart text-primary me-1"></i>
                            Usage Limit <span class="text-danger">*</span>
                        </label>

                        <input type="number"
       name="usage_limit"
       value="{{ old('usage_limit') }}"
       class="form-control @error('usage_limit') is-invalid @enderror"
       placeholder="Enter usage limit"
       min="1">

@error('usage_limit')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror


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
                                value="{{ old('starts_at') }}"
                                class="form-control @error('starts_at') is-invalid @enderror">

                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror


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
                                value="{{ old('expires_at') }}"
                                class="form-control @error('expires_at') is-invalid @enderror">

                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text mt-2">
                            When the coupon expires
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 6: BANK OFFER FIELDS ================= -->
        <div class="bank-field d-none mb-5">
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-university text-primary me-2"></i>Bank Offer Details
                </h6>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-bank text-primary me-1"></i>Bank
                        </label>

                        <select name="bank_id" id="bank_id" class="form-select">
    <option value="">Select Bank</option>

    @foreach($banks as $bank)
        <option value="{{ $bank->id }}">
            {{ $bank->name }}
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
                        <select name="card_type" id="card_type" class="form-select">
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                            <option value="both">EMI</option>
                        </select>
                        <div class="form-text mt-2">
                            Type of cards eligible for this offer
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="mb-5">

    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-3">
            <i class="fas fa-box text-primary me-2"></i>
            Product Restriction
        </h6>
    </div>

    <div class="row g-4">

        <div class="col-md-3">
            <label class="form-label fw-semibold">
                Category
            </label>

            <select
                name="category_id"
                id="category_id"
                class="form-select">

                <option value="">
                    All Categories
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}">

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>
        </div>

        <div class="col-md-3">

            <label class="form-label fw-semibold">
                Sub Category
            </label>

            <select
                name="subcategory_id"
                id="subcategory_id"
                class="form-select">

                <option value="">
                    Select Sub Category
                </option>

            </select>

        </div>

        <div class="col-md-3">

            <label class="form-label fw-semibold">
                Product
            </label>

            <select
                name="product_id"
                id="product_id"
                class="form-select">

                <option value="">
                    Select Product
                </option>

            </select>

        </div>

        <div class="col-md-3">

            <label class="form-label fw-semibold">
                One Time Per User
            </label>

            <select
                name="one_time_per_user"
                class="form-select">

                <option value="0">
                    No
                </option>

                <option value="1">
                    Yes
                </option>

            </select>

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
                            <option value="1" class="text-success">
                                <i class="fas fa-check-circle me-1"></i> Active
                            </option>
                            <option value="0" class="text-danger">
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
                        <i class="fas fa-plus-circle me-2"></i> Create Coupon
                    </button>
                </div>
            </div>
        </div>

        </form>
    </div>
</div>

</div>


@endsection