<div id="platformPricingSection" style="display: none;">

@foreach($platforms as $platform)
<div class="card mb-4 shadow-sm platform-pricing-card"
     id="platformPricingCard_{{ $platform->id }}"
     style="display: none;">

@php
    $platformName = strtolower(trim($platform->name));
@endphp

<div class="card-header bg-white fw-semibold d-flex align-items-center gap-3">

    <div class="w-16 h-10 flex items-center">

        {{-- AMAZON --}}
        @if(str_contains($platformName,'amazon'))
            <svg viewBox="0 0 200 60" class="h-6 w-auto" preserveAspectRatio="xMidYMid meet">
                <text x="0" y="42" font-size="38" font-weight="700" fill="#111" font-family="Arial, Helvetica, sans-serif">
                    amazon
                </text>
                <path d="M10 50 C40 70, 120 70, 150 50"
                      stroke="#FF9900" stroke-width="5"
                      fill="none" stroke-linecap="round"/>
            </svg>

        {{-- FLIPKART --}}
        @elseif(str_contains($platformName,'flipkart'))
            <svg viewBox="0 0 64 64" class="h-8 w-auto" preserveAspectRatio="xMidYMid meet">
                <rect width="64" height="64" rx="14" fill="#2874F0"/>
                <text x="32" y="44" text-anchor="middle"
                      font-size="40" font-weight="800"
                      fill="#FFD700"
                      font-family="Arial, Helvetica, sans-serif">F</text>
            </svg>

   {{-- WEBSITE --}}
@elseif(str_contains($platformName,'website'))

<span class="text-xl leading-none">🌐</span>

{{-- DEFAULT --}}
@else
    <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
@endif


    </div>

    <span>{{ ucfirst($platform->name) }} </span>
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
       class="form-control platform-calc-input platform-price-input"
       placeholder="0.00"
       min="0"
       value="0">

        <div class="form-text">
            Selling price on {{ ucfirst($platform->name) }}
        </div>
    </div>
<!-- QUANTITY -->
<div class="col-md-2">
    <label class="form-label fw-semibold">
        Quantity <span class="text-danger">*</span>
    </label>

    <input type="number"
           id="quantity_{{ $platform->id }}"
           class="form-control platform-calc-input platform-qty-input"
           placeholder="0"
           min="0"
           value="0">

    <!-- 🔥 LIVE STOCK INFO -->
    <div class="small mt-1 text-muted stock-display">
        Available Stock:
        <strong class="available-stock">0</strong>
        &nbsp;|&nbsp;
        Remaining:
        <strong class="remaining-stock">0</strong>
    </div>
</div>

<!-- DISCOUNT SMART INPUT -->
<div class="col-md-3">
    <label class="form-label fw-semibold">Discount</label>

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



    <!-- FINAL TOTAL -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">
            Total Price After Discount (₹)
        </label>
        <input type="text"
               id="final_total_{{ $platform->id }}"
               class="form-control bg-light fw-bold text-success"
               value="0.00"
               readonly>
        <div class="form-text">Auto calculated on full quantity</div>
    </div>

</div>
    </div>
</div>
@endforeach

</div>
