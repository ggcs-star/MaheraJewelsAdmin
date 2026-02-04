<div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">📦 Select Product</div>
    <div class="card-body">

        <label class="fw-semibold">Product Name *</label>

<select id="productSelect" class="form-select">
    <option value="">Select product</option>

    @foreach ($products as $product)
<option value="{{ $product->id }}"
    data-category="{{ $product->display_category }}"
    data-subcategory="{{ $product->display_subcategory }}"
    data-sku="{{ $product->sku }}"
    data-slug="{{ $product->slug }}"
    data-brand="{{ $product->brand }}"
data-warehouse="{{ $product->warehouse->name ?? '' }}"
    data-variants='@json($product->variant_payload)'>
    {{ $product->name }}
</option>

    @endforeach
</select>

    </div>
</div>
