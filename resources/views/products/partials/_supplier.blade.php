@php
    /** @var \App\Models\Product|null $product */
@endphp
@php
    $product = $product ?? null;
@endphp

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🚚</span>
        <span>Supplier</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Supplier Select -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Select Supplier
                </label>

                <select id="supplierSelect"
                        name="supplier_id"
                        class="form-select">
                    <option value="">Select supplier</option>

                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}"
                            data-name="{{ $sup->name }}"
                            data-company="{{ $sup->company_name }}"
                            data-phone="{{ $sup->phone }}"
                            data-email="{{ $sup->email }}"
                            data-type="{{ ucfirst($sup->type) }}"
                            data-commission="{{ $sup->commission_type }} ({{ $sup->commission_value }})"
                            {{ old('supplier_id', $product->supplier_id ?? '') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                            @if($sup->company_name)
                                ({{ $sup->company_name }})
                            @endif
                        </option>
                    @endforeach
                </select>

                <div class="form-text">
                    Optional – used for purchase & commission calculation.
                </div>
            </div>

            <!-- Supplier Details -->
            <div class="col-md-12 {{ old('supplier_id', $product->supplier_id ?? false) ? '' : 'd-none' }}"
                 id="supplierBox">

                <div class="border rounded bg-light p-3">
                    <div class="fw-semibold mb-2 text-primary">
                        Supplier Details
                    </div>

                    <div class="row g-2 small">
                        <div class="col-md-3">
                            <div class="text-muted">Name</div>
                            <div id="sName">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Company</div>
                            <div id="sCompany">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Phone</div>
                            <div id="sPhone">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Email</div>
                            <div id="sEmail">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Type</div>
                            <div id="sType">—</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted">Commission</div>
                            <div id="sCommission" class="fw-semibold text-danger">—</div>
                        </div>

                        <hr class="my-3">

<div class="fw-semibold mb-2 text-primary">
    Procurement Details
</div>

<div class="row g-3">

    {{-- 🏬 Warehouse --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Warehouse (City)
        </label>

        <select name="warehouse_id" class="form-select">
            <option value="">Select warehouse</option>

            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}"
                    {{ old('warehouse_id', $product->warehouse_id ?? '') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->city }} — {{ $wh->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- 📦 Expected Delivery Date --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Expected Delivery Date
        </label>

        <input type="date"
               name="expected_delivery_date"
               class="form-control"
value="{{ old(
    'expected_delivery_date',
    optional($product)->expected_delivery_date
        ? \Carbon\Carbon::parse($product->expected_delivery_date)->format('Y-m-d')
        : ''
) }}">

</div>
{{-- 💳 Payment Terms --}}
<div class="col-md-4">
    <label class="form-label fw-semibold">
        Payment Terms
    </label>

    @php
        $savedTerms = old('payment_terms', $product->payment_terms ?? '');
        $isCustom = $savedTerms && !in_array($savedTerms, ['advance','net_7','net_15','net_30']);
        $customDays = $isCustom ? str_replace('net_', '', $savedTerms) : '';
    @endphp

    <select id="paymentTermsSelect" class="form-select">
        <option value="">Select terms</option>
        <option value="advance" {{ $savedTerms === 'advance' ? 'selected' : '' }}>Advance</option>
        <option value="net_7" {{ $savedTerms === 'net_7' ? 'selected' : '' }}>7 Days</option>
        <option value="net_15" {{ $savedTerms === 'net_15' ? 'selected' : '' }}>15 Days</option>
        <option value="net_30" {{ $savedTerms === 'net_30' ? 'selected' : '' }}>30 Days</option>
        <option value="custom" {{ $isCustom ? 'selected' : '' }}>Custom (Days)</option>
    </select>

    {{-- 👇 THIS is where you type days --}}
    <input type="number"
           id="customPaymentDays"
           class="form-control mt-2 {{ $isCustom ? '' : 'd-none' }}"
           placeholder="Enter days (e.g. 60, 90)"
           min="1"
           value="{{ $customDays }}">

    {{-- hidden field that actually saves to DB --}}
    <input type="hidden"
           name="payment_terms"
           id="finalPaymentTerms"
           value="{{ $savedTerms }}">
</div>

</div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
