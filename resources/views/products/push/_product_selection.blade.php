<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">📦</span>
        <span>Select Product from Inventory</span>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <!-- Product Name Dropdown -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    Product Name <span class="text-danger">*</span>
                </label>
                <select id="productSelect" 
                        name="product_id" 
                        class="form-select form-select-lg"
                        required>
                    <option value="">-- Select a product from inventory --</option>
                    @forelse($products ?? [] as $product)
                        <option value="{{ $product->id }}" 
                                data-category="{{ $product->category?->name ?? 'N/A' }}"
                                data-subcategory="{{ $product->category?->parent?->name ?? ($product->category?->name ?? 'N/A') }}"
                                data-sku="{{ $product->sku }}"
                                data-slug="{{ $product->slug }}"
                                data-brand="{{ $product->brand ?? 'N/A' }}"
                                data-variants="{{ htmlspecialchars(json_encode($product->variants ?? []), ENT_QUOTES, 'UTF-8') }}">
                            {{ $product->name }} (SKU: {{ $product->sku }}) - {{ $product->variants->count() ?? 0 }} variants
                        </option>
                    @empty
                        <option value="" disabled>No products available in inventory</option>
                    @endforelse
                </select>
                <div class="form-text">
                    Select a product that you've already added to inventory.
                </div>
            </div>
        </div>
    </div>
</div>
