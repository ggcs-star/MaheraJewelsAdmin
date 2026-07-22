<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="fw-bold mb-0">📦 Select Product</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <label class="fw-semibold mb-2">Product Name <span class="text-danger">*</span></label>
                <select id="productSelect" name="product_id" class="form-select form-select-lg" required>
                    <option value="">— Select product —</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-category="{{ $product->display_category }}"
                            data-subcategory="{{ $product->display_subcategory }}"
                            data-sku="{{ $product->sku }}"
                            data-slug="{{ $product->slug }}"
                            data-brand="{{ $product->brand }}"
                            data-warehouse="{{ $product->warehouse->name ?? '' }}"
                            data-image="{{ $product->image }}"
                            data-variants='@json($product->variant_payload)'>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="w-100">
                    <small class="text-muted">Selected Product</small>
                    <div id="selectedProductName" class="fw-bold text-primary">—</div>
                </div>
            </div>
        </div>
    </div>
</div>