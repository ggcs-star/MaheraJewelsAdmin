<div id="platformPricingSection" style="display: none;">
    
    <!-- Website Pricing -->
    <div class="card mb-4 shadow-sm platform-pricing-card" id="websitePricingCard" style="display: none;">
        <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
            <span class="text-primary fs-5">🌐</span>
            <span>Website Pricing & Inventory</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price (₹) <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[website][price]" 
                           id="website_price"
                           class="form-control platform-calc-input" 
                           data-platform="website"
                           placeholder="0.00"
                           min="0"
                           value="0">
                    <div class="form-text">Selling price on website</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Discount (%)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[website][discount]" 
                           id="website_discount"
                           class="form-control platform-calc-input" 
                           data-platform="website"
                           placeholder="0"
                           min="0"
                           max="100"
                           value="0">
                    <div class="form-text">Discount percentage</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="platforms[website][quantity]" 
                           id="website_quantity"
                           class="form-control platform-calc-input" 
                           data-platform="website"
                           placeholder="0"
                           min="0"
                           value="0">
                    <div class="form-text">Available stock for website</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price Calculation
                    </label>
                    <div class="border rounded p-3 bg-light h-100">
                        <div class="small text-muted mb-2">Per Product After Discount</div>
                        <div class="fw-bold text-primary fs-5 mb-2" id="website_price_after_discount">₹0.00</div>
                        <div class="border-top pt-2 mt-2">
                            <div class="small text-muted mb-1">Total Value</div>
                            <div class="fw-bold text-success fs-4" id="website_total">₹0.00</div>
                        </div>
                        <div class="small text-muted mt-2" id="website_calculation">
                            <span id="website_formula">Price × Qty = Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flipkart Pricing -->
    <div class="card mb-4 shadow-sm platform-pricing-card" id="flipkartPricingCard" style="display: none;">
        <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
            <span class="text-warning fs-5">🛒</span>
            <span>Flipkart Pricing & Inventory</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price (₹) <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[flipkart][price]" 
                           id="flipkart_price"
                           class="form-control platform-calc-input" 
                           data-platform="flipkart"
                           placeholder="0.00"
                           min="0"
                           value="0">
                    <div class="form-text">Selling price on Flipkart</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Discount (%)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[flipkart][discount]" 
                           id="flipkart_discount"
                           class="form-control platform-calc-input" 
                           data-platform="flipkart"
                           placeholder="0"
                           min="0"
                           max="100"
                           value="0">
                    <div class="form-text">Discount percentage</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="platforms[flipkart][quantity]" 
                           id="flipkart_quantity"
                           class="form-control platform-calc-input" 
                           data-platform="flipkart"
                           placeholder="0"
                           min="0"
                           value="0">
                    <div class="form-text">Available stock for Flipkart</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price Calculation
                    </label>
                    <div class="border rounded p-3 bg-light h-100">
                        <div class="small text-muted mb-2">Per Product After Discount</div>
                        <div class="fw-bold text-primary fs-5 mb-2" id="flipkart_price_after_discount">₹0.00</div>
                        <div class="border-top pt-2 mt-2">
                            <div class="small text-muted mb-1">Total Value</div>
                            <div class="fw-bold text-success fs-4" id="flipkart_total">₹0.00</div>
                        </div>
                        <div class="small text-muted mt-2" id="flipkart_calculation">
                            <span id="flipkart_formula">Price × Qty = Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Amazon Pricing -->
    <div class="card mb-4 shadow-sm platform-pricing-card" id="amazonPricingCard" style="display: none;">
        <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
            <span class="text-warning fs-5">📦</span>
            <span>Amazon Pricing & Inventory</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price (₹) <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[amazon][price]" 
                           id="amazon_price"
                           class="form-control platform-calc-input" 
                           data-platform="amazon"
                           placeholder="0.00"
                           min="0"
                           value="0">
                    <div class="form-text">Selling price on Amazon</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Discount (%)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="platforms[amazon][discount]" 
                           id="amazon_discount"
                           class="form-control platform-calc-input" 
                           data-platform="amazon"
                           placeholder="0"
                           min="0"
                           max="100"
                           value="0">
                    <div class="form-text">Discount percentage</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="platforms[amazon][quantity]" 
                           id="amazon_quantity"
                           class="form-control platform-calc-input" 
                           data-platform="amazon"
                           placeholder="0"
                           min="0"
                           value="0">
                    <div class="form-text">Available stock for Amazon</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Price Calculation
                    </label>
                    <div class="border rounded p-3 bg-light h-100">
                        <div class="small text-muted mb-2">Per Product After Discount</div>
                        <div class="fw-bold text-primary fs-5 mb-2" id="amazon_price_after_discount">₹0.00</div>
                        <div class="border-top pt-2 mt-2">
                            <div class="small text-muted mb-1">Total Value</div>
                            <div class="fw-bold text-success fs-4" id="amazon_total">₹0.00</div>
                        </div>
                        <div class="small text-muted mt-2" id="amazon_calculation">
                            <span id="amazon_formula">Price × Qty = Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
