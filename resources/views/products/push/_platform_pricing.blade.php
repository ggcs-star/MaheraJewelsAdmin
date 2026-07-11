@php
    // ✅ Modal se variantId le rahe hain
    $variantId = $variantId ?? null;
    
    // ✅ Debug ke liye (baad mein hata dena)
    // dump('Variant ID in platform pricing: ' . $variantId);
    
    $poData = isset($purchaseOrderData[$variantId]) ? $purchaseOrderData[$variantId] : null;
    $poQty = $poData['quantity'] ?? 0;
    $poPrice = $poData['purchase_price'] ?? 0;
    $availableStock = $poData['available_stock'] ?? $poQty;
    $defaultQty = $availableStock;
@endphp

<div class="row g-3">

    <input type="hidden" id="variantId" value="{{ $variantId ?? '' }}">

    <div class="col-md-3">
        <label class="form-label fw-semibold text-muted">📦 PO Quantity</label>
        <input type="text"
               id="po_quantity_{{ $platform->id }}"
               class="form-control bg-light"
               value="{{ $poQty }}"
               readonly
               style="background-color: #f8f9fa; cursor: not-allowed; font-weight: bold; color: #1a7a3a;">
        <div class="form-text text-muted">From Purchase Order</div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold text-muted">📦 PO Price (₹)</label>
        <input type="text"
               id="po_price_{{ $platform->id }}"
               class="form-control bg-light"
               value="{{ $poPrice }}"
               readonly
               style="background-color: #f8f9fa; cursor: not-allowed; font-weight: bold; color: #8B2452;">
        <div class="form-text text-muted">From Purchase Order</div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">💰 Selling Price (₹) <span class="text-danger">*</span></label>
        <input type="number"
            step="0.01"
            id="price_{{ $platform->id }}"
            class="form-control platform-calc-input platform-price-input"
            placeholder="Enter selling price"
            min="0"
            value="">
        <div class="form-text">Set selling price manually</div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">📊 Quantity</label>
        <input type="number"
               id="quantity_{{ $platform->id }}"
               class="form-control platform-calc-input platform-qty-input"
               placeholder="0"
               min="0"
               value="{{ $defaultQty }}"
               style="font-weight: bold; color: #1a7a3a;">
        <div class="small mt-1 text-muted stock-display">
            Available: <strong class="available-stock">{{ $availableStock }}</strong>
            &nbsp;|&nbsp;
            Remaining: <strong class="remaining-stock">{{ $availableStock - $defaultQty }}</strong>
        </div>
        <div id="stockError_{{ $platform->id }}" class="text-danger small" style="display:none;">
            ⚠️ Quantity exceeds available stock!
        </div>
    </div>

</div>

<div class="row g-3 mt-2">

    <div class="col-md-3">
        <label class="form-label fw-semibold">🏷️ Discount</label>
        <div class="input-group">
            <input type="number"
                   step="0.01"
                   id="discount_value_{{ $platform->id }}"
                   class="form-control platform-calc-input"
                   placeholder="0"
                   min="0"
                   value="0">
            <select id="discount_type_{{ $platform->id }}"
                    class="form-select"
                    style="max-width:80px;">
                <option value="amount">₹</option>
                <option value="percent">%</option>
            </select>
        </div>
        <div class="form-text">Choose ₹ or %</div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">💵 Total (₹)</label>
        <input type="text"
               id="final_total_{{ $platform->id }}"
               class="form-control bg-light fw-bold text-success"
               value="0.00"
               readonly>
        <div class="form-text">Auto calculated</div>
    </div>

</div>

@if($poData)
    <div class="alert alert-info alert-sm py-2 px-3 mt-3 mb-0">
        <small>
            <strong>📦 Purchase Order:</strong>
            PO: {{ $poData['po_number'] ?? 'N/A' }} |
            Qty: {{ $poData['quantity'] }} |
            Price: ₹{{ number_format($poData['purchase_price'], 2) }}
        </small>
    </div>
@endif

