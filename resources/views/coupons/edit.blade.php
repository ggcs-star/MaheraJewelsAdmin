@extends('layouts.admin')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin/coupon-push.js') }}"></script>
@endpush

<div class="container-fluid px-4 py-5">

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-xl font-bold text-gray-800 mb-1">
                    Edit Coupon
                </h4>
                <p class="text-sm text-gray-500">Update coupon details and configuration</p>
            </div>
            <div>
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium" style="background: rgba(68, 12, 44, 0.1); color: #440C2C;">
                    Editing Mode
                </span>
            </div>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-6">
            <div class="mb-3">
                <h6 class="font-semibold text-gray-800 mb-1">
                    Platform Selection <span class="text-red-500">*</span>
                </h6>
                <p class="text-xs text-gray-500">Select platforms where this coupon will be valid</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($platforms->take(3) as $platform)
                    @php
                        $checked = $coupon->platforms->contains($platform->id);
                        $platform_lower = strtolower($platform->name);
                    @endphp
                    
                    <label class="cursor-pointer m-0">
                        <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}" {{ $checked ? 'checked' : '' }} class="hidden">
                        
                        <div class="border rounded-lg p-4 h-full transition-all {{ $checked ? 'border-2' : 'border-gray-200' }}" style="{{ $checked ? 'border-color: #440C2C; background: rgba(68, 12, 44, 0.05);' : '' }}">
                            <div class="flex items-center mb-3">
                                @if($platform->icon && file_exists(public_path($platform->icon)))
                                    <div class="mr-3">
                                        <img src="{{ asset($platform->icon) }}" alt="{{ $platform->name }} icon" height="32" class="img-fluid">
                                    </div>
                                @else
                                    <div class="mr-3">
                                        @if(str_contains($platform_lower, 'amazon'))
                                            <svg viewBox="0 0 200 60" style="height:32px" preserveAspectRatio="xMidYMid meet">
                                                <text x="0" y="42" font-size="38" font-weight="700" fill="#111" font-family="Arial, Helvetica, sans-serif">amazon</text>
                                                <path d="M10 50 C40 70, 120 70, 150 50" stroke="#FF9900" stroke-width="5" fill="none" stroke-linecap="round"/>
                                            </svg>
                                        @elseif(str_contains($platform_lower, 'flipkart'))
                                            <svg viewBox="0 0 64 64" style="height:32px" preserveAspectRatio="xMidYMid meet">
                                                <rect width="64" height="64" rx="14" fill="#2874F0"/>
                                                <text x="32" y="44" text-anchor="middle" font-size="40" font-weight="800" fill="#FFD700" font-family="Arial, Helvetica, sans-serif">F</text>
                                            </svg>
                                        @elseif(str_contains($platform_lower, 'own') || str_contains($platform_lower, 'website'))
                                            <span style="font-size: 1.8rem;">🌐</span>
                                        @else
                                            <span style="font-size: 1.6rem;">🏬</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h6 class="font-semibold text-gray-800 mb-0">{{ $platform->name }}</h6>
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $checked ? 'text-white' : 'bg-gray-100 text-gray-600' }}" style="{{ $checked ? 'background: #440C2C;' : '' }}">
                                        {{ $checked ? 'Selected' : 'Select' }}
                                    </span>
                                </div>
                                
                                @if($checked)
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5" style="color: #440C2C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="text-xs text-gray-400">
                                {{ $checked ? 'Selected for this coupon' : 'Click to select this platform' }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-6">
            <h6 class="font-semibold text-gray-800 mb-3">
                Basic Information
            </h6>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Coupon Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="coupon_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                           value="{{ old('coupon_name', $coupon->name) }}"
                           required>
                    <div class="text-xs text-gray-400 mt-1">A descriptive name for this coupon</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Coupon Code
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </span>
                        <input type="text" 
                               name="code" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg bg-gray-50 text-gray-500"
                               value="{{ old('code', $coupon->code) }}"
                               disabled>
                    </div>
                    <div class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Coupon code cannot be modified
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="coupon_description" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                              rows="3">{{ old('coupon_description', $coupon->description) }}</textarea>
                    <div class="text-xs text-gray-400 mt-1">Optional details about this coupon offer</div>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-6">
            <h6 class="font-semibold text-gray-800 mb-3">
                Discount Settings
            </h6>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Coupon Type <span class="text-red-500">*</span>
                    </label>
                    <select name="coupon_type" id="coupon_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white" required>
                        <option value="NORMAL" {{ $coupon->coupon_type === 'NORMAL' ? 'selected' : '' }}>Coupon</option>
                        <option value="BANK" {{ $coupon->coupon_type === 'BANK' ? 'selected' : '' }}>Bank Offer</option>
                    </select>
                    <div class="text-xs text-gray-400 mt-1">Select the type of coupon</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Discount Type <span class="text-red-500">*</span>
                    </label>
                    <select name="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white" required>
                        <option value="FLAT" {{ $coupon->discount_type === 'FLAT' ? 'selected' : '' }}>Flat Amount</option>
                        <option value="PERCENT" {{ $coupon->discount_type === 'PERCENT' ? 'selected' : '' }}>Percentage</option>
                    </select>
                    <div class="text-xs text-gray-400 mt-1">How the discount is calculated</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Discount Value <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <input type="number" 
                               name="value" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               value="{{ old('value', $coupon->value) }}"
                               required
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-500">₹</span>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">The discount amount or percentage</div>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-6">
            <h6 class="font-semibold text-gray-800 mb-3">
                Rules & Limitations
            </h6>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Minimum Order Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-500">₹</span>
                        <input type="number" 
                               name="min_order_amount" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Minimum cart value to use this coupon</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Max Discount
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-500">₹</span>
                        <input type="number" 
                               name="max_discount" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               value="{{ old('max_discount', $coupon->max_discount) }}"
                               step="0.01"
                               min="0"
                               placeholder="No limit">
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Maximum discount applicable</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Usage Limit <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="usage_limit" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                           value="{{ old('usage_limit', $coupon->usage_limit) }}"
                           min="0"
                           placeholder="Unlimited">
                    <div class="text-xs text-gray-400 mt-1">Maximum number of times this coupon can be used</div>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-6">
            <h6 class="font-semibold text-gray-800 mb-3">
                Validity Period
            </h6>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="date" 
                               name="starts_at" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="text-xs text-gray-400 mt-1">When the coupon becomes valid</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Expiry Date <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="date" 
                               name="expires_at" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                               value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                               min="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="text-xs text-gray-400 mt-1">When the coupon expires</div>
                </div>
            </div>
        </div>
<hr class="my-6 border-gray-200">

<div class="mb-6">
    <h6 class="font-semibold text-gray-800 mb-3">
        Product Restriction
    </h6>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <label>Category</label>

            <select id="category_id" name="category_id" class="w-full border rounded-lg px-3 py-2">

                <option value="">All Categories</option>

                @foreach($categories as $category)

                    <option value="{{ $category->id }}"
                        {{ $coupon->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label>Sub Category</label>

            <select id="subcategory_id" name="subcategory_id" class="w-full border rounded-lg px-3 py-2">

                <option value="">Select Sub Category</option>

                @foreach($subCategories as $sub)

                    <option value="{{ $sub->id }}"
                        {{ $coupon->subcategory_id == $sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div>

            <label>Product</label>

            <select id="product_id" name="product_id" class="w-full border rounded-lg px-3 py-2">

                <option value="">Select Product</option>

                @foreach($products as $product)

                    <option value="{{ $product->id }}"
                        {{ $coupon->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div>

            <label>One Time Per User</label>

            <select name="one_time_per_user" class="w-full border rounded-lg px-3 py-2">

                <option value="1"
                    {{ $coupon->one_time_per_user ? 'selected' : '' }}>
                    Yes
                </option>

                <option value="0"
                    {{ !$coupon->one_time_per_user ? 'selected' : '' }}>
                    No
                </option>

            </select>

        </div>

    </div>

</div>
        <div class="bank-field {{ $coupon->coupon_type === 'BANK' ? '' : 'hidden' }} mb-6">
            <hr class="my-6 border-gray-200">
            <h6 class="font-semibold text-gray-800 mb-3">
                Bank Offer Details
            </h6>

            @php
                $bank = $coupon->bank;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bank
                    </label>
                    <select name="bank_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white">
                        <option value="">Select Bank</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->id }}" {{ $bank && $bank->id == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-xs text-gray-400 mt-1">Select bank for this offer</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Card Type
                    </label>
                    <select name="card_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white">
                        <option value="credit" {{ $coupon->card_type === 'credit' ? 'selected' : '' }}>Credit Card</option>
                        <option value="debit" {{ $coupon->card_type === 'debit' ? 'selected' : '' }}>Debit Card</option>
                        <option value="both" {{ $coupon->card_type === 'both' ? 'selected' : '' }}>EMI</option>
                    </select>
                    <div class="text-xs text-gray-400 mt-1">Type of cards eligible for this offer</div>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-6">
            <h6 class="font-semibold text-gray-800 mb-3">
                Status
            </h6>

            <div>
                <select name="is_active" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white" required>
                    <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
                <div class="text-xs text-gray-400 mt-1">Set coupon availability</div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-5 mt-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Back to List
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium transition" style="background: #440C2C; color: white;">
                        Update Coupon
                    </button>
                </div>
            </div>
        </div>

        </form>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const couponType = document.getElementById('coupon_type');
    const bankField = document.querySelector('.bank-field');
    
    function toggleBankField() {
        if (couponType.value === 'BANK') {
            bankField.classList.remove('hidden');
        } else {
            bankField.classList.add('hidden');
        }
    }
    
    if (couponType) {
        couponType.addEventListener('change', toggleBankField);
        toggleBankField();
    }
});
const category = document.getElementById('category_id');
const subCategory = document.getElementById('subcategory_id');
const product = document.getElementById('product_id');

category?.addEventListener('change', async function () {

    const res = await fetch('/admin/coupons/subcategories/' + this.value);

    const data = await res.json();

    subCategory.innerHTML =
        '<option value="">Select Sub Category</option>';

    data.forEach(item => {

        subCategory.innerHTML +=
            `<option value="${item.id}">${item.name}</option>`;

    });

    product.innerHTML =
        '<option value="">Select Product</option>';

});

subCategory?.addEventListener('change', async function () {

    const res = await fetch('/admin/coupons/products/' + this.value);

    const data = await res.json();

    product.innerHTML =
        '<option value="">Select Product</option>';

    data.forEach(item => {

        product.innerHTML +=
            `<option value="${item.id}">${item.name}</option>`;

    });

});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection