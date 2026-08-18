@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
<style>
    /* ============================================
       LIGHT MAROON THEME - EXTRA STYLES
       ============================================ */
    
    .coupon-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8e8f0;
        box-shadow: 0 2px 12px rgba(107, 26, 58, 0.06);
        overflow: hidden;
    }

    /* Header - Light Maroon */
    .coupon-header {
        background: linear-gradient(135deg, #8B2452 0%, #6B1A3A 100%);
        padding: 16px 24px;
        border-bottom: 2px solid #F4B94E;
    }
    .coupon-header h4 {
        color: #ffffff;
        font-weight: 600;
        font-size: 20px;
        margin-bottom: 2px;
    }
    .coupon-header h4 i {
        color: #F4B94E;
    }
    .coupon-header p {
        color: rgba(255,255,255,0.75);
        font-size: 13px;
        margin-bottom: 0;
    }
    .coupon-header p i {
        color: rgba(255,255,255,0.5);
    }

    /* Badge Gold */
    .badge-gold {
        background: rgba(244, 185, 78, 0.12);
        color: #F4B94E;
        padding: 4px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
        border: 1px solid rgba(244, 185, 78, 0.15);
    }
    .badge-gold i {
        color: #F4B94E;
    }

    /* Section Wrapper */
    .section-wrapper {
        background: #fafafc;
        border-radius: 10px;
        padding: 16px 20px;
        border: 1px solid #e8e8f0;
        transition: all 0.3s ease;
        margin-bottom: 16px;
    }
    .section-wrapper:hover {
        border-color: #B84A6A;
    }

    /* Section Title */
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0;
    }
    .section-title .icon {
        width: 28px;
        height: 28px;
        background: rgba(139, 36, 82, 0.06);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B1A3A;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        font-size: 12px;
        color: #4a4a6a;
        margin-bottom: 4px;
        display: block;
    }
    .form-label .required {
        color: #dc3545;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e8e8f0;
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6B1A3A;
        box-shadow: 0 0 0 3px rgba(139, 36, 82, 0.06);
        outline: none;
    }
    .form-control:disabled {
        background: #f5f5f5;
        cursor: not-allowed;
    }

    .form-text {
        font-size: 11px;
        color: #8a8aaa;
        margin-top: 3px;
    }
    .form-text i {
        color: #B84A6A;
        margin-right: 3px;
    }

    /* Platform Cards */
    .platform-card {
        border: 1.5px solid #e8e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    .platform-card:hover {
        border-color: #B84A6A;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 26, 58, 0.06);
    }
    .platform-card.selected {
        border-color: #6B1A3A;
        background: rgba(139, 36, 82, 0.04);
        box-shadow: 0 0 0 3px rgba(139, 36, 82, 0.06);
    }
    .platform-card .check-icon {
        color: #6B1A3A;
        font-size: 18px;
    }
    .platform-card .icon-box {
        width: 36px;
        height: 36px;
        background: rgba(139, 36, 82, 0.06);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .platform-card h6 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 0;
    }

    /* Buttons */
    .btn-theme-primary {
        background: #6B1A3A;
        border: none;
        color: white;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-theme-primary:hover {
        background: #8B2452;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 26, 58, 0.2);
        text-decoration: none;
    }

    .btn-theme-outline {
        background: transparent;
        border: 1.5px solid #6B1A3A;
        color: #6B1A3A;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-theme-outline:hover {
        background: #6B1A3A;
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-theme-light {
        background: transparent;
        border: 1.5px solid #e8e8f0;
        color: #4a4a6a;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-theme-light:hover {
        border-color: #6B1A3A;
        color: #6B1A3A;
        background: rgba(139, 36, 82, 0.04);
        text-decoration: none;
    }

    .badge-theme {
        background: rgba(139, 36, 82, 0.06);
        color: #6B1A3A;
        padding: 2px 10px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 9px;
        display: inline-block;
    }

    /* Divider */
    .divider {
        border: none;
        height: 1px;
        background: #e8e8f0;
        margin: 16px 0;
    }

    /* Input Group */
    .input-group-theme .input-group-text {
        background: rgba(139, 36, 82, 0.04);
        border: 1.5px solid #e8e8f0;
        color: #6B1A3A;
        font-weight: 600;
        font-size: 13px;
        border-radius: 8px 0 0 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
    }
    .input-group-theme .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Spacing */
    .mb-4 {
        margin-bottom: 12px !important;
    }
    .gap-3 {
        gap: 8px !important;
    }
    .gap-4 {
        gap: 12px !important;
    }
    .p-4 {
        padding: 20px !important;
    }
    .p-md-5 {
        padding: 24px !important;
    }
    .card-body {
        padding: 20px 24px !important;
    }
    .row.g-4 {
        --bs-gutter-y: 12px;
        --bs-gutter-x: 16px;
    }
    .row.g-3 {
        --bs-gutter-y: 10px;
        --bs-gutter-x: 12px;
    }
    .container-fluid {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }
    .py-4 {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .coupon-header { padding: 12px 16px; }
        .coupon-header h4 { font-size: 17px; }
        .section-wrapper { padding: 12px 16px; }
        .card-body { padding: 12px 16px !important; }
        .btn-theme-primary,
        .btn-theme-outline,
        .btn-theme-light {
            padding: 6px 16px;
            font-size: 12px;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.4s ease forwards;
    }

    .d-none {
        display: none !important;
    }
</style>
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
    toggleFields();

    // ✅ Platform card toggle - FIXED (Edit)
    document.querySelectorAll('.platform-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault(); // ✅ Prevent any default behavior
            const checkbox = this.closest('label').querySelector('input[type="checkbox"]');
            if (checkbox) {
                // ✅ Toggle checkbox
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected');
                const checkIcon = this.querySelector('.check-icon');
                if (checkIcon) {
                    checkIcon.classList.toggle('d-none');
                }
                // ✅ Update badge text
                const badge = this.querySelector('.badge-theme');
                if (badge) {
                    badge.textContent = checkbox.checked ? 'Selected' : 'Select';
                }
                // ✅ Trigger change event for validation
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });

    // Bank fields toggle
    const couponType = document.getElementById('coupon_type');
    const bankFields = document.querySelector('.bank-field');
    if (couponType && bankFields) {
        function toggleBankFields() {
            if (couponType.value === 'BANK') {
                bankFields.classList.remove('d-none');
            } else {
                bankFields.classList.add('d-none');
            }
        }
        couponType.addEventListener('change', toggleBankFields);
        toggleBankFields();
    }
});

// Category → Subcategory → Product dropdown
const category = document.getElementById('category_id');
const subCategory = document.getElementById('subcategory_id');
const product = document.getElementById('product_id');

category?.addEventListener('change', async function () {
    const res = await fetch('/admin/coupons/subcategories/' + this.value);
    const data = await res.json();
    subCategory.innerHTML = '<option value="">Select Sub Category</option>';
    data.forEach(item => {
        subCategory.innerHTML += `<option value="${item.id}">${item.name}</option>`;
    });
    product.innerHTML = '<option value="">Select Product</option>';
});

subCategory?.addEventListener('change', async function () {
    const res = await fetch('/admin/coupons/products/' + this.value);
    const data = await res.json();
    product.innerHTML = '<option value="">Select Product</option>';
    data.forEach(item => {
        product.innerHTML += `<option value="${item.id}">${item.name}</option>`;
    });
});
</script>
@endpush

<div class="container-fluid py-3">

<div class="coupon-card animate-fade-in">

    <!-- ===== HEADER - Light Maroon ===== -->
    <div class="coupon-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4>
                    <i class="fas fa-edit me-2"></i>Edit Coupon
                </h4>
                <p>
                    <i class="fas fa-pen-fancy me-1"></i>Update coupon details and configuration
                </p>
            </div>
            <div>
                <span class="badge-gold">
                    <i class="fas fa-ticket-alt me-1"></i> {{ $coupon->code }}
                </span>
            </div>
        </div>
    </div>

    <!-- ===== BODY ===== -->
    <div class="card-body">

<form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
    @csrf
    @method('PUT')

    <!-- ===== ERRORS ===== -->
    @if ($errors->any())
    <div class="section-wrapper mb-3" style="border-color: #dc3545; background: rgba(220, 53, 69, 0.04);">
        <div class="d-flex align-items-start gap-2">
            <div style="color: #dc3545; font-size: 16px;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <h6 class="fw-bold" style="color: #dc3545; font-size: 13px; margin-bottom: 2px;">Please fix the following errors:</h6>
                <ul class="mb-0 ps-3" style="color: #dc3545; font-size: 12px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- ===== GENERATE TYPE ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-cog"></i>
            </div>
            <h6 class="section-title">Generate Type</h6>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-code-branch me-1"></i>Generate Type <span class="required">*</span>
                </label>
                <select name="generate_type" 
                        id="generate_type" 
                        class="form-select @error('generate_type') is-invalid @enderror">
                    <option value="single" {{ old('generate_type', $coupon->generate_type ?? 'single') == 'single' ? 'selected' : '' }}>
                        Single Coupon
                    </option>
                    <option value="bulk" {{ old('generate_type', $coupon->generate_type ?? 'single') == 'bulk' ? 'selected' : '' }}>
                        Bulk Coupon
                    </option>
                </select>
                @error('generate_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle"></i> Choose how to generate coupons
                </div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== PLATFORMS ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-desktop"></i>
            </div>
            <div>
                <h6 class="section-title">Platform Selection <span class="required">*</span></h6>
                <span class="form-text mb-0 d-block">Choose platforms where this coupon will be valid</span>
            </div>
        </div>

        <div class="row g-3">
            @foreach($platforms->take(3) as $platform)
                @php
                    $platform_lower = strtolower($platform->name);
                    $checked = $coupon->platforms->contains($platform->id);
                @endphp
                
                <div class="col-md-4">
                    <label class="d-block m-0 cursor-pointer">
                        <input type="checkbox"
                            name="platform_ids[]"
                            value="{{ $platform->id }}"
                            style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;"
                            {{ $checked ? 'checked' : '' }}>
                        <div class="platform-card {{ $checked ? 'selected' : '' }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-box">
                                    @if($platform->icon && file_exists(public_path($platform->icon)))
                                        <img src="{{ asset($platform->icon) }}" alt="{{ $platform->name }}" height="20">
                                    @else
                                        @if(str_contains($platform_lower, 'amazon'))
                                            <i class="fab fa-amazon" style="color: #FF9900; font-size: 18px;"></i>
                                        @elseif(str_contains($platform_lower, 'flipkart'))
                                            <i class="fas fa-shopping-bag" style="color: #2874F0; font-size: 18px;"></i>
                                        @elseif(str_contains($platform_lower, 'own') || str_contains($platform_lower, 'website'))
                                            <i class="fas fa-globe" style="color: #6B1A3A; font-size: 16px;"></i>
                                        @else
                                            <i class="fas fa-store" style="color: #6B1A3A; font-size: 16px;"></i>
                                        @endif
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6>{{ $platform->name }}</h6>
                                    <span class="badge-theme">{{ $checked ? 'Selected' : 'Select' }}</span>
                                </div>
                                
                                <div class="check-icon {{ $checked ? '' : 'd-none' }}">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <hr class="divider">

    <!-- ===== BASIC INFORMATION ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <h6 class="section-title">Basic Information</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-tag me-1"></i>Coupon Name <span class="required">*</span>
                </label>
                <input type="text"
                    name="coupon_name"
                    value="{{ old('coupon_name', $coupon->name) }}"
                    class="form-control @error('coupon_name') is-invalid @enderror"
                    placeholder="e.g., Summer Sale Discount">
                @error('coupon_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-lightbulb"></i> A descriptive name for this coupon
                </div>
            </div>

            <div class="col-md-6" id="coupon_code_field">
                <label class="form-label">
                    <i class="fas fa-hashtag me-1"></i>Coupon Code <span class="required">*</span>
                </label>
                <input type="text"
                    name="code"
                    value="{{ old('code', $coupon->code) }}"
                    class="form-control @error('code') is-invalid @enderror"
                    placeholder="e.g., SUMMER25"
                    disabled>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-key"></i> Unique code customers will enter at checkout
                </div>
                <div class="form-text" style="color: #B8860B;">
                    <i class="fas fa-exclamation-triangle"></i> Coupon code cannot be modified
                </div>
            </div>

            <div id="bulk_fields" style="{{ (old('generate_type', $coupon->generate_type ?? 'single') === 'bulk') ? '' : 'display: none;' }}" class="col-12">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-flag me-1"></i>Campaign Name <span class="required">*</span>
                        </label>
                        <input type="text"
                            name="campaign_name"
                            value="{{ old('campaign_name', $coupon->campaign_name) }}"
                            class="form-control @error('campaign_name') is-invalid @enderror"
                            placeholder="e.g., Summer Sale 2024">
                        @error('campaign_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-font me-1"></i>Coupon Prefix <span class="required">*</span>
                        </label>
                        <input type="text"
                            name="prefix"
                            value="{{ old('prefix', $coupon->prefix) }}"
                            class="form-control @error('prefix') is-invalid @enderror"
                            placeholder="e.g., SALE">
                        @error('prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-sort-numeric-up me-1"></i>Quantity <span class="required">*</span>
                        </label>
                        <input type="number"
                            name="quantity"
                            value="{{ old('quantity', $coupon->quantity) }}"
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

            <div class="col-12">
                <label class="form-label">
                    <i class="fas fa-align-left me-1"></i>Description
                </label>
                <textarea
                    name="coupon_description"
                    class="form-control"
                    rows="2">{{ old('coupon_description', $coupon->description) }}</textarea>
                <div class="form-text">
                    <i class="fas fa-comment-dots"></i> Optional details about this coupon offer
                </div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== DISCOUNT SETTINGS ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-percentage"></i>
            </div>
            <h6 class="section-title">Discount Settings</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-gift me-1"></i>Coupon Type <span class="required">*</span>
                </label>
                <select name="coupon_type"
                        id="coupon_type"
                        class="form-select @error('coupon_type') is-invalid @enderror">
                    <option value="">Select type</option>
                    <option value="NORMAL" {{ old('coupon_type', $coupon->coupon_type) === 'NORMAL' ? 'selected' : '' }}>
                        Coupon
                    </option>
                    <!-- <option value="BANK" {{ old('coupon_type', $coupon->coupon_type) === 'BANK' ? 'selected' : '' }}>
                        Bank Offer
                    </option> -->
                </select>
                @error('coupon_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-question-circle"></i> Select the type of coupon
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-calculator me-1"></i>Discount Type <span class="required">*</span>
                </label>
                <select name="discount_type"
                        class="form-select @error('discount_type') is-invalid @enderror">
                    <option value="">Select discount type</option>
                    <option value="FLAT" {{ old('discount_type', $coupon->discount_type) === 'FLAT' ? 'selected' : '' }}>
                        Flat Amount
                    </option>
                    <option value="PERCENT" {{ old('discount_type', $coupon->discount_type) === 'PERCENT' ? 'selected' : '' }}>
                        Percentage
                    </option>
                </select>
                @error('discount_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-chart-line"></i> How the discount is calculated
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-rupee-sign me-1"></i>Discount Value <span class="required">*</span>
                </label>
                <div class="input-group input-group-theme">
                    <span class="input-group-text">₹</span>
                    <input type="number"
                        name="value"
                        value="{{ old('value', $coupon->value) }}"
                        class="form-control @error('value') is-invalid @enderror"
                        placeholder="0.00"
                        step="0.01"
                        min="0">
                </div>
                @error('value')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-money-bill-wave"></i> The discount amount or percentage
                </div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== RULES ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <h6 class="section-title">Rules & Limitations</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-shopping-cart me-1"></i>Min Order Amount
                </label>
                <div class="input-group input-group-theme">
                    <span class="input-group-text">₹</span>
                    <input type="number"
                        name="min_order_amount"
                        value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                        class="form-control @error('min_order_amount') is-invalid @enderror"
                        placeholder="0.00"
                        step="0.01"
                        min="0">
                </div>
                @error('min_order_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Minimum cart value to use this coupon</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-chart-line me-1"></i>Max Discount
                </label>
                <div class="input-group input-group-theme">
                    <span class="input-group-text">₹</span>
                    <input type="number" 
                        name="max_discount"
                        value="{{ old('max_discount', $coupon->max_discount) }}"
                        class="form-control"
                        placeholder="No limit"
                        step="0.01"
                        min="0">
                </div>
                <div class="form-text">Maximum discount applicable</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    <i class="fas fa-shopping-cart me-1"></i>Usage Limit <span class="required">*</span>
                </label>
                <input type="number"
                    name="usage_limit"
                    value="{{ old('usage_limit', $coupon->usage_limit) }}"
                    class="form-control @error('usage_limit') is-invalid @enderror"
                    placeholder="Enter usage limit"
                    min="1">
                @error('usage_limit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maximum number of times this coupon can be used</div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== VALIDITY ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h6 class="section-title">Validity Period</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-calendar-day me-1"></i>Start Date <span class="required">*</span>
                </label>
                <div class="input-group input-group-theme">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    <input type="date"
                        name="starts_at"
                        value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}"
                        class="form-control @error('starts_at') is-invalid @enderror">
                </div>
                @error('starts_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">When the coupon becomes valid</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-calendar-times me-1"></i>Expiry Date <span class="required">*</span>
                </label>
                <div class="input-group input-group-theme">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    <input type="date"
                        name="expires_at"
                        value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                        class="form-control @error('expires_at') is-invalid @enderror">
                </div>
                @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">When the coupon expires</div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== BANK OFFER ===== -->
    <div class="bank-field {{ old('coupon_type', $coupon->coupon_type) === 'BANK' ? '' : 'd-none' }} section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-university"></i>
            </div>
            <h6 class="section-title">Bank Offer Details</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-bank me-1"></i>Bank
                </label>
                <select name="bank_id" id="bank_id" class="form-select">
                    <option value="">Select Bank</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}" {{ old('bank_id', $coupon->bank_id) == $bank->id ? 'selected' : '' }}>
                            {{ $bank->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Select bank for this offer</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-credit-card me-1"></i>Card Type
                </label>
                <select name="card_type" id="card_type" class="form-select">
                    <option value="credit" {{ old('card_type', $coupon->card_type) === 'credit' ? 'selected' : '' }}>Credit Card</option>
                    <option value="debit" {{ old('card_type', $coupon->card_type) === 'debit' ? 'selected' : '' }}>Debit Card</option>
                    <option value="both" {{ old('card_type', $coupon->card_type) === 'both' ? 'selected' : '' }}>EMI</option>
                </select>
                <div class="form-text">Type of cards eligible for this offer</div>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== PRODUCT RESTRICTION ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <h6 class="section-title">Product Restriction</h6>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $coupon->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Sub Category</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select">
                    <option value="">Select Sub Category</option>
                    @foreach($subCategories as $sub)
                        <option value="{{ $sub->id }}" {{ old('subcategory_id', $coupon->subcategory_id) == $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Product</label>
                <select name="product_id" id="product_id" class="form-select">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $coupon->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">One Time Per User</label>
                <select name="one_time_per_user" class="form-select">
                    <option value="0" {{ old('one_time_per_user', $coupon->one_time_per_user) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('one_time_per_user', $coupon->one_time_per_user) == '1' ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="divider">

    <!-- ===== STATUS ===== -->
    <div class="section-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="icon">
                <i class="fas fa-toggle-on"></i>
            </div>
            <h6 class="section-title">Status</h6>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $coupon->is_active) == '1' ? 'selected' : '' }} style="color: #28a745;">
                        <i class="fas fa-check-circle me-1"></i> Active
                    </option>
                    <option value="0" {{ old('is_active', $coupon->is_active) == '0' ? 'selected' : '' }} style="color: #dc3545;">
                        <i class="fas fa-times-circle me-1"></i> Inactive
                    </option>
                </select>
                <div class="form-text">Set coupon availability</div>
            </div>
        </div>
    </div>

    <!-- ===== FORM ACTIONS ===== -->
    <div class="border-top pt-3 mt-3" style="border-color: #e8e8f0;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('admin.coupons.index') }}" class="btn-theme-outline">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn-theme-light">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn-theme-primary">
                    <i class="fas fa-save me-1"></i> Update Coupon
                </button>
            </div>
        </div>
    </div>

</form>
    </div>
</div>

</div>
@endsection