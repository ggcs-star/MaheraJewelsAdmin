@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">📝</span>
        <span>Basic Information</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Product Name <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter product name"
                       value="{{ old('name', $product->name ?? '') }}"
                       required>
                <div class="form-text">
                    This name will be visible to customers.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    SKU <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="sku"
                       class="form-control"
                       placeholder="Unique product code"
                       value="{{ old('sku', $product->sku ?? '') }}"
                       required>
                <div class="form-text">
                    Must be unique for inventory tracking.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Slug <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="slug"
                       class="form-control"
                       placeholder="product-name-slug"
                       value="{{ old('slug', $product->slug ?? '') }}"
                       required>
                <div class="form-text">
                    Used in product URL.
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Product Price <span class="text-danger">*</span>
                </label>

                <input type="number"
                    name="product_price"
                    class="form-control"
                    step="0.01"
                    min="0"
                    placeholder="Enter product price"
                    value="{{ old('product_price', $product->product_price ?? '') }}"
                    required>

                <div class="form-text">
                    Default price shown before selecting variant.
                </div>
            </div>
            
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    Short Description
                </label>
                <textarea name="short_description"
                          class="form-control"
                          rows="2"
                          placeholder="One-line summary of the product">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                <div class="form-text">
                    Displayed in product listings.
                </div>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    Full Description
                </label>
                <textarea name="description"
                          class="form-control"
                          rows="4"
                          placeholder="Detailed product description">{{ old('description', $product->description ?? '') }}</textarea>
                <div class="form-text">
                    Shown on the product detail page.
                </div>
            </div>

        </div>
    </div>
</div>
