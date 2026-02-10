@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2">
        <h6 class="mb-0 fw-bold text-primary">Edit Coupon</h6>
        <p class="text-muted mb-0" style="font-size: 0.85rem;">Update the details of your coupon offer</p>
    </div>

    <div class="card-body p-3">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
        @csrf
        @method('PUT')

        {{-- ================= PLATFORMS (TOP 3 WITH ICONS) ================= --}}
        <div class="mb-4">
            <label class="fw-bold mb-2 d-block" style="font-size: 0.9rem;">Select Platforms</label>
            <p class="text-muted mb-2" style="font-size: 0.8rem;">Choose platforms where this coupon will be valid</p>

            <div class="d-flex gap-3">
                @foreach($platforms->take(3) as $platform)
                    @php
                        $checked = $coupon->platforms->contains($platform->id);
                    @endphp

                    <label class="text-center position-relative" style="min-width: 100px;">
                        <input
                            type="checkbox"
                            name="platform_ids[]"
                            value="{{ $platform->id }}"
                            {{ $checked ? 'checked' : '' }}
                            hidden
                        >

                        <div class="border rounded-2 p-2 platform-box bg-white {{ $checked ? 'border-primary border-2' : '' }}"
                             style="font-size: 0.85rem; {{ $checked ? 'background-color: #f8f9ff !important;' : '' }}">
                            
                            {{-- Platform Icon --}}
                            <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 40px;">
                                @if($platform->icon && file_exists(public_path($platform->icon)))
                                    <img src="{{ asset($platform->icon) }}" height="28" alt="{{ $platform->name }} icon" 
                                         class="img-fluid">
                                @else
                                    @php
                                        $platform_lower = strtolower($platform->name);
                                    @endphp
                                    
                                    <div class="rounded-circle p-2 
                                        @if(str_contains($platform_lower, 'amazon')) bg-warning bg-opacity-10 @endif
                                        @if(str_contains($platform_lower, 'flipkart')) bg-primary bg-opacity-10 @endif
                                        @if(str_contains($platform_lower, 'own') || str_contains($platform_lower, 'website')) bg-success bg-opacity-10 @endif">
                                        
                                        @if(str_contains($platform_lower, 'amazon'))
                                            <i class="fab fa-amazon text-warning" style="font-size: 1.2rem;"></i>
                                        @elseif(str_contains($platform_lower, 'flipkart'))
                                            <i class="fas fa-shopping-bag text-primary" style="font-size: 1.2rem;"></i>
                                        @elseif(str_contains($platform_lower, 'own') || str_contains($platform_lower, 'website'))
                                            <i class="fas fa-globe text-success" style="font-size: 1.2rem;"></i>
                                        @else
                                            <i class="fas fa-store text-muted" style="font-size: 1.2rem;"></i>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <div class="fw-medium text-dark mb-1">{{ $platform->name }}</div>
                            <div class="mt-1">
                                <span class="badge bg-light text-muted" style="font-size: 0.75rem;">
                                    {{ $checked ? 'Selected' : 'Select' }}
                                </span>
                            </div>
                            
                            @if($checked)
                                <div class="position-absolute top-0 end-0 m-1" style="font-size: 0.9rem;">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <hr class="my-4">

        {{-- ================= ALL FIELDS (ONE FLOW) ================= --}}
        <div class="row g-3">

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Coupon Name <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="coupon_name"
                        class="form-control form-control-sm border-secondary-subtle"
                        value="{{ old('coupon_name', $coupon->name) }}"
                        required
                        style="font-size: 0.9rem;"
                    >
                    <div class="form-text" style="font-size: 0.8rem;">A descriptive name for this coupon</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Coupon Code <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="code"
                        class="form-control form-control-sm border-secondary-subtle bg-light"
                        value="{{ old('code', $coupon->code) }}"
                        disabled
                        style="font-size: 0.9rem;"
                    >
                    <div class="form-text text-warning" style="font-size: 0.8rem;">
                        <i class="fas fa-lock me-1"></i> Coupon code cannot be changed
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Description</label>
                    <textarea 
                        name="coupon_description" 
                        class="form-control form-control-sm border-secondary-subtle"
                        rows="2"
                        style="font-size: 0.9rem;"
                    >{{ old('coupon_description', $coupon->description) }}</textarea>
                    <div class="form-text" style="font-size: 0.8rem;">Optional details about this coupon offer</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Coupon Type <span class="text-danger">*</span>
                    </label>
                    <select name="coupon_type" id="coupon_type" class="form-select form-select-sm border-secondary-subtle" required style="font-size: 0.9rem;">
                        <option value="NORMAL" {{ $coupon->coupon_type === 'NORMAL' ? 'selected' : '' }}>Normal Coupon</option>
                        <option value="BANK" {{ $coupon->coupon_type === 'BANK' ? 'selected' : '' }}>Bank Offer</option>
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Select the type of coupon</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Discount Type <span class="text-danger">*</span>
                    </label>
                    <select name="discount_type" class="form-select form-select-sm border-secondary-subtle" required style="font-size: 0.9rem;">
                        <option value="FLAT" {{ $coupon->discount_type === 'FLAT' ? 'selected' : '' }}>Flat Amount</option>
                        <option value="PERCENT" {{ $coupon->discount_type === 'PERCENT' ? 'selected' : '' }}>Percentage</option>
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">How the discount is calculated</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Discount Value <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-sm">
                        <input
                            type="number"
                            name="value"
                            class="form-control form-control-sm border-secondary-subtle"
                            value="{{ old('value', $coupon->value) }}"
                            required
                            step="0.01"
                            style="font-size: 0.9rem;"
                        >
                        <span class="input-group-text bg-light border-secondary-subtle" style="font-size: 0.9rem;">₹</span>
                    </div>
                    <div class="form-text" style="font-size: 0.8rem;">The discount amount or percentage</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Minimum Order Amount</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="number"
                            name="min_order_amount"
                            class="form-control form-control-sm border-secondary-subtle"
                            value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                            step="0.01"
                            style="font-size: 0.9rem;"
                        >
                        <span class="input-group-text bg-light border-secondary-subtle" style="font-size: 0.9rem;">₹</span>
                    </div>
                    <div class="form-text" style="font-size: 0.8rem;">Minimum cart value to use this coupon</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Max Discount</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="number"
                            name="max_discount"
                            class="form-control form-control-sm border-secondary-subtle"
                            value="{{ old('max_discount', $coupon->max_discount) }}"
                            step="0.01"
                            style="font-size: 0.9rem;"
                        >
                        <span class="input-group-text bg-light border-secondary-subtle" style="font-size: 0.9rem;">₹</span>
                    </div>
                    <div class="form-text" style="font-size: 0.8rem;">Maximum discount applicable</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Usage Limit</label>
                    <input
                        type="number"
                        name="usage_limit"
                        class="form-control form-control-sm border-secondary-subtle"
                        value="{{ old('usage_limit', $coupon->usage_limit) }}"
                        style="font-size: 0.9rem;"
                    >
                    <div class="form-text" style="font-size: 0.8rem;">Maximum number of times this coupon can be used</div>
                </div>
            </div>

          <div class="col-md-6">
    <div class="form-group mb-3">
        <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
            Start Date
        </label>

        <input
            type="date"
            name="starts_at"
            class="form-control form-control-sm border-secondary-subtle"
            value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}"
            style="font-size: 0.9rem;"
        >

        <div class="form-text" style="font-size: 0.8rem;">
            When the coupon becomes valid
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
            Expiry Date
        </label>

        <input
            type="date"
            name="expires_at"
            class="form-control form-control-sm border-secondary-subtle"
            value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
            min="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}"
            style="font-size: 0.9rem;"
        >

        <div class="form-text" style="font-size: 0.8rem;">
            When the coupon expires
        </div>
    </div>
</div>


                        @php
                            $bank = $coupon->bank;
                        @endphp

            <div class="col-md-6 bank-field {{ $coupon->coupon_type === 'BANK' ? '' : 'd-none' }}">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Bank</label>
                    <select name="bank_id" class="form-select form-select-sm border-secondary-subtle" style="font-size: 0.9rem;">
                        <option value="">Select Bank</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->id }}" {{ $bank && $bank->id == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Select bank for this offer</div>
                </div>
            </div>

            <div class="col-md-6 bank-field {{ $coupon->coupon_type === 'BANK' ? '' : 'd-none' }}">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Card Type</label>
                    <select name="card_type" class="form-select form-select-sm border-secondary-subtle" style="font-size: 0.9rem;">
                        <option value="credit" {{ $coupon->card_type === 'credit' ? 'selected' : '' }}>Credit Card</option>
                        <option value="debit" {{ $coupon->card_type === 'debit' ? 'selected' : '' }}>Debit Card</option>
                        <option value="both" {{ $coupon->card_type === 'both' ? 'selected' : '' }}>EMI</option>

                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Type of cards eligible for this offer</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="is_active" class="form-select form-select-sm border-secondary-subtle" required style="font-size: 0.9rem;">
                        <option value="1" {{ $coupon->is_active ? 'selected' : '' }} class="text-success">Active</option>
                        <option value="0" {{ !$coupon->is_active ? 'selected' : '' }} class="text-danger">Inactive</option>
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Set coupon availability</div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4 pt-3 border-top">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary px-3 me-2" style="font-size: 0.85rem;">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
            <button class="btn btn-sm btn-primary px-4 fw-semibold" style="font-size: 0.85rem;">
                <i class="fas fa-save me-1"></i> Update Coupon
            </button>
        </div>

        </form>
    </div>
</div>

</div>

<script>
const couponType = document.getElementById('coupon_type');

function toggleBankFields() {
    document.querySelectorAll('.bank-field').forEach(el => {
        el.classList.toggle('d-none', couponType.value !== 'BANK');
    });
}

couponType.addEventListener('change', toggleBankFields);
toggleBankFields();

// ✅ PLATFORM SELECT – CORRECT WAY
document.querySelectorAll('input[name="platform_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const box = this.nextElementSibling;
        const badge = box.querySelector('.badge');

        if (this.checked) {
            box.classList.add('border-primary', 'border-2');
            box.style.backgroundColor = '#f8f9ff';
            if (badge) badge.textContent = 'Selected';
        } else {
            box.classList.remove('border-primary', 'border-2');
            box.style.backgroundColor = '';
            if (badge) badge.textContent = 'Select';
        }
    });
});
</script>

<style>
.platform-box {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

.platform-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    border-color: #adb5bd;
}

.border-primary {
    border-color: #0d6efd !important;
    background-color: #f8f9ff !important;
}

.form-control, .form-select {
    border-radius: 6px;
    transition: all 0.15s;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.card {
    border-radius: 8px;
}

hr {
    opacity: 0.2;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
}

.form-control-sm, .form-select-sm {
    padding: 0.25rem 0.5rem;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection