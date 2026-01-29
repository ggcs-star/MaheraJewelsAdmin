<div id="platformPricingSection" style="display: none;">

@foreach($platforms as $platform)
<div class="card mb-4 shadow-sm platform-pricing-card"
     id="platformPricingCard_{{ $platform->id }}"
     style="display: none;">

    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="fs-5">🌐</span>
        <span>{{ ucfirst($platform->name) }} Pricing & Inventory</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- PRICE -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Price (₹) <span class="text-danger">*</span>
                </label>
                <input type="number"
                       step="0.01"
                       id="price_{{ $platform->id }}"
                       class="form-control platform-calc-input"
                       placeholder="0.00"
                       min="0"
                       value="0">
                <div class="form-text">
                    Selling price on {{ ucfirst($platform->name) }}
                </div>
            </div>

            <!-- DISCOUNT -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Discount (%)
                </label>
                <input type="number"
                       step="0.01"
                       id="discount_{{ $platform->id }}"
                       class="form-control platform-calc-input"
                       placeholder="0"
                       min="0"
                       max="100"
                       value="0">
                <div class="form-text">Discount percentage</div>
            </div>

            <!-- QUANTITY -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Quantity <span class="text-danger">*</span>
                </label>
                <input type="number"
                       id="quantity_{{ $platform->id }}"
                       class="form-control platform-calc-input"
                       placeholder="0"
                       min="0"
                       value="0">
                <div class="form-text">
                    Available stock for {{ ucfirst($platform->name) }}
                </div>
            </div>

            <!-- CALCULATION -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Price Calculation
                </label>

                <div class="border rounded p-3 bg-light h-100">
                    <div class="small text-muted mb-2">
                        Per Product After Discount
                    </div>

                    <div class="fw-bold text-primary fs-5 mb-2"
                         id="price_after_discount_{{ $platform->id }}">
                        ₹0.00
                    </div>

                    <div class="border-top pt-2 mt-2">
                        <div class="small text-muted mb-1">
                            Total Value
                        </div>
                        <div class="fw-bold text-success fs-4"
                             id="total_{{ $platform->id }}">
                            ₹0.00
                        </div>
                    </div>

                    <div class="small text-muted mt-2"
                         id="calculation_{{ $platform->id }}">
                        Price × Qty = Total
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach

</div>
