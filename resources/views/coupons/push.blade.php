@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2">
        <h6 class="mb-0 fw-bold text-primary">Create New Coupon</h6>
        <p class="text-muted mb-0" style="font-size: 0.85rem;">Fill in the details below to create a new coupon offer</p>
    </div>

    <div class="card-body p-3">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf

        {{-- ================= PLATFORMS (TOP 3) ================= --}}
        <div class="mb-4">
            <label class="fw-bold mb-2 d-block" style="font-size: 0.9rem;">Select Platforms</label>
            <p class="text-muted mb-2" style="font-size: 0.8rem;">Choose platforms where this coupon will be valid</p>

            <div class="d-flex gap-3">
                @foreach($platforms->take(3) as $platform)
                    <label class="text-center position-relative" style="min-width: 100px;">
                        <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}" hidden>
                        <div class="border rounded-2 p-2 platform-box bg-white" style="font-size: 0.85rem;">
                            <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 40px;">
                                @if($platform->icon)
                                    <img src="{{ asset($platform->icon) }}" height="28" alt="{{ $platform->name }} icon" 
                                         class="img-fluid">
                                @else
                                    <div class="bg-light rounded-circle p-2">
                                        <i class="fas fa-store" style="font-size: 0.9rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="fw-medium text-dark mb-1">{{ $platform->name }}</div>
                            <div class="mt-1">
                                <span class="badge bg-light text-muted" style="font-size: 0.75rem;">Select</span>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 m-1 check-icon d-none" style="font-size: 0.9rem;">
                            <i class="fas fa-check-circle text-primary"></i>
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
                    <input type="text" name="coupon_name" class="form-control form-control-sm border-secondary-subtle" 
                           placeholder="e.g., Summer Sale Discount" required style="font-size: 0.9rem;">
                    <div class="form-text" style="font-size: 0.8rem;">A descriptive name for this coupon</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Coupon Code <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-sm border-secondary-subtle" 
                           placeholder="e.g., SUMMER25" required style="font-size: 0.9rem;">
                    <div class="form-text" style="font-size: 0.8rem;">Unique code customers will enter at checkout</div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Description</label>
                    <textarea name="coupon_description" class="form-control form-control-sm border-secondary-subtle" 
                              rows="2" placeholder="Describe the coupon offer..." style="font-size: 0.9rem;"></textarea>
                    <div class="form-text" style="font-size: 0.8rem;">Optional details about this coupon offer</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">
                        Coupon Type <span class="text-danger">*</span>
                    </label>
                    <select name="coupon_type" id="coupon_type" class="form-select form-select-sm border-secondary-subtle" required style="font-size: 0.9rem;">
                        <option value="NORMAL"> Coupon</option>
                        <option value="BANK">Bank Offer</option>
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
                        <option value="FLAT">Flat Amount</option>
                        <option value="PERCENT">Percentage</option>
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
                        <input type="number" name="value" class="form-control form-control-sm border-secondary-subtle" 
                               placeholder="0.00" required step="0.01" style="font-size: 0.9rem;">
                        <span class="input-group-text bg-light border-secondary-subtle" style="font-size: 0.9rem;">₹</span>
                    </div>
                    <div class="form-text" style="font-size: 0.8rem;">The discount amount or percentage</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Minimum Order Amount</label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="min_order_amount" class="form-control form-control-sm border-secondary-subtle" 
                               placeholder="0.00" step="0.01" style="font-size: 0.9rem;">
                        <span class="input-group-text bg-light border-secondary-subtle" style="font-size: 0.9rem;">₹</span>
                    </div>
                    <div class="form-text" style="font-size: 0.8rem;">Minimum cart value to use this coupon</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control form-control-sm border-secondary-subtle" 
                           placeholder="Unlimited (leave empty)" style="font-size: 0.9rem;">
                    <div class="form-text" style="font-size: 0.8rem;">Maximum number of times this coupon can be used</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Start Date</label>
                    <input type="date" name="starts_at" class="form-control form-control-sm border-secondary-subtle" style="font-size: 0.9rem;">
                    <div class="form-text" style="font-size: 0.8rem;">When the coupon becomes valid</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control form-control-sm border-secondary-subtle" style="font-size: 0.9rem;">
                    <div class="form-text" style="font-size: 0.8rem;">When the coupon expires</div>
                </div>
            </div>

            {{-- 🔹 BANK FIELDS (INLINE – NO SEPARATE SECTION) --}}
            <div class="col-md-6 bank-field d-none">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Bank</label>
                    <select name="bank_id" class="form-select form-select-sm border-secondary-subtle" style="font-size: 0.9rem;">
                        <option value="">Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Select bank for this offer</div>
                </div>
            </div>

            <div class="col-md-6 bank-field d-none">
                <div class="form-group mb-3">
                    <label class="fw-semibold mb-1 text-dark" style="font-size: 0.9rem;">Card Type</label>
                    <select name="card_type" class="form-select form-select-sm border-secondary-subtle" style="font-size: 0.9rem;">
                        <option value="credit">Credit Card</option>
                        <option value="debit">Debit Card</option>
                        <option value="both">EMI</option>
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
                        <option value="1" class="text-success">Active</option>
                        <option value="0" class="text-danger">Inactive</option>
                    </select>
                    <div class="form-text" style="font-size: 0.8rem;">Set coupon availability</div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4 pt-3 border-top">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary px-3 me-2" style="font-size: 0.85rem;">Cancel</a>
            <button class="btn btn-sm btn-primary px-4 fw-semibold" style="font-size: 0.85rem;">
                <i class="fas fa-plus-circle me-1"></i> Create Coupon
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

document.querySelectorAll('input[name="platform_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const box = this.nextElementSibling;
        const checkIcon = box.parentElement.querySelector('.check-icon');

        if (this.checked) {
            box.classList.add('border-primary', 'border-2');
            box.style.backgroundColor = '#f8f9ff';
            checkIcon.classList.remove('d-none');
        } else {
            box.classList.remove('border-primary', 'border-2');
            box.style.backgroundColor = '';
            checkIcon.classList.add('d-none');
        }
    });
});

document.querySelector('input[name="starts_at"]').valueAsDate = new Date();
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

.check-icon {
    z-index: 10;
    text-shadow: 0 0 2px white;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
}

.form-control-sm, .form-select-sm {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection