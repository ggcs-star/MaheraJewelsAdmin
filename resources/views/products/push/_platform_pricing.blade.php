@php
    $variantId = $variantId ?? null;
    $poData = isset($purchaseOrderData[$variantId]) ? $purchaseOrderData[$variantId] : null;
    $poQty = $poData['quantity'] ?? 0;
    $poPrice = $poData['purchase_price'] ?? 0;
    $availableStock = $poData['available_stock'] ?? $poQty;
    $defaultQty = $availableStock > 0 ? $availableStock : 0;
@endphp

<div class="platform-pricing-card" id="platformPricingCard_{{ $platform->id }}" style="display:none;">

    <div class="row g-1 align-items-center">

        {{-- Platform Name --}}
        <div class="col-md-2">
            <div class="d-flex align-items-center gap-1">
                @php $name = strtolower($platform->name); @endphp
                @if(str_contains($name, 'amazon'))
                    <span class="fw-bold" style="color:#232F3E;">Amazon</span>
                @elseif(str_contains($name, 'flipkart'))
                    <span class="fw-bold" style="color:#2874F0;">Flipkart</span>
                @elseif(str_contains($name, 'website'))
                    <span>🌐 <span class="fw-semibold">Own Website</span></span>
                @elseif(str_contains($name, 'offline'))
                    <span>🏪 <span class="fw-semibold">Offline</span></span>
                @elseif(str_contains($name, 'meesho'))
                    <span>🛍️ <span class="fw-semibold">Meesho</span></span>
                @else
                    <span class="fw-semibold">{{ ucfirst($platform->name) }}</span>
                @endif
            </div>
        </div>

        {{-- Allocate Stock --}}
        <div class="col-md-1">
            <input type="number"
                   id="quantity_{{ $platform->id }}"
                   class="form-control form-control-sm platform-qty-input"
                   placeholder="0"
                   min="0"
                   value="{{ $defaultQty }}"
                   style="font-weight:bold; color:#1a7a3a; width:60px;">
        </div>

        {{-- Selling Price --}}
        <div class="col-md-1">
            <input type="number"
                   step="0.01"
                   id="price_{{ $platform->id }}"
                   class="form-control form-control-sm platform-price-input"
                   placeholder="0.00"
                   min="0"
                   value=""
                   style="width:80px;">
        </div>

        {{-- Discount --}}
        <div class="col-md-1">
            <div class="input-group input-group-sm" style="width:90px;">
                <input type="number"
                       step="0.01"
                       id="discount_value_{{ $platform->id }}"
                       class="form-control"
                       placeholder="0"
                       min="0"
                       value="0"
                       style="width:50px;">
                <select id="discount_type_{{ $platform->id }}"
                        class="form-select" style="width:40px; font-size:10px; padding:0 2px;">
                    <option value="amount">₹</option>
                    <option value="percent">%</option>
                </select>
            </div>
            <small class="text-muted d-block" id="discount_text_{{ $platform->id }}" style="font-size:9px;"></small>
        </div>

        {{-- Final Price --}}
        <div class="col-md-1">
            <input type="text"
                   id="final_total_{{ $platform->id }}"
                   class="form-control form-control-sm bg-light fw-bold text-success"
                   value="₹ 0.00"
                   readonly
                   style="background:#f8f9fa !important; width:80px; text-align:right;">
        </div>

        {{-- Total Selling --}}
        <div class="col-md-2">
            <input type="text"
                   id="total_selling_{{ $platform->id }}"
                   class="form-control form-control-sm bg-light fw-bold text-primary"
                   value="₹ 0.00"
                   readonly
                   style="background:#f8f9fa !important; width:110px; text-align:right;">
        </div>

        {{-- Status --}}
        <div class="col-md-1">
            <span class="badge bg-success" id="platform_status_{{ $platform->id }}">Active</span>
        </div>

    </div>

    {{-- Hidden fields --}}
    <input type="hidden" id="po_quantity_{{ $platform->id }}" value="{{ $poQty }}">
    <input type="hidden" id="po_price_{{ $platform->id }}" value="{{ $poPrice }}">
    <input type="hidden" id="po_available_{{ $platform->id }}" value="{{ $availableStock }}">

</div>