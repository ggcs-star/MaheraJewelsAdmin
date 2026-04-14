<style>
.color-cell {
    position: relative;
}

.variant-color {
    position: relative;
    z-index: 1;
}

.color-blocker {
    position: absolute;
    inset: 0;
    z-index: 9999;         
    background: transparent;
    cursor: not-allowed;
    display: none;
    pointer-events: all;    
}
.badge {
    display: inline-block;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 20px;
}
.bg-info {
    background-color: #17a2b8;
    color: white;
}
.btn-sm {
    padding: 4px 10px;
    font-size: 12px;
}
</style>

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-semibold fs-6">
            📦 Product Variants
        </div>
        <button type="button" id="addVariantBtn" class="btn btn-sm btn-outline-primary">
            + Add Variant
        </button>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="variantTable">
                <thead class="table-light">
                    <tr class="text-center align-middle">
                        <th>Type</th>
                        <th>Value</th>
                       <th>Color</th>
                        <th>SKU</th>
                        <th>Height</th>
                        <th>Width</th>
                        <th>Image</th>
                        <th style="width:70px;text-align:center;">Sort</th>
                        <th style="width:120px;text-align:center;">Status</th>
                        <th style="width:80px;text-align:center;">Qty</th>

                        <th>Purchase</th>
                        <th>Selling</th>
                        <th>Total</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($product) && $product->variants->count())
                        @foreach($product->variants as $i => $variant)
                            <tr class="variant-row">
                                <!-- Type -->
                              <td>
                    <select
                        name="variants[{{ $i }}][variant_id]"
                        class="form-control variant-type">

                            <option value="">Select</option>

                                @foreach($variants as $masterVariant)
                            <option value="{{ $masterVariant->id }}"
                                    data-input-type="{{ $masterVariant->input_type }}"
                                    data-has-dimensions="{{ $masterVariant->has_dimensions }}"
                                    {{ $variant->variant_id == $masterVariant->id ? 'selected' : '' }}>
                                {{ $masterVariant->name }}
                            </option>
                            @endforeach
                                </select>
                            </td>

                            <td class="variant-value-cell">
                                <select
                                    name="variants[{{ $i }}][variant_value_id]"
                                    class="form-control variant-value">

                                    <option value="">Select value</option>

                                    @foreach($variants as $masterVariant)
                                        @if($masterVariant->id == $variant->variant_id)
                                            @foreach($masterVariant->values as $val)
                                                <option value="{{ $val->id }}"
                                                    {{ $variant->variant_value_id == $val->id ? 'selected' : '' }}>
                                                    {{ $val->value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach

                                </select>
                            </td>

                            <td class="color-cell">
                                <input type="color"
                                    name="variants[{{ $i }}][color]"
                                    class="form-control form-control-color variant-color"
                                    value="{{ $variant->color ?? '' }}">

                                <div class="color-blocker"></div>
                            </td>


                                <!-- SKU -->
                                <td>
                                    <input type="text" name="variants[{{ $i }}][sku_suffix]" class="form-control"
                                        value="{{ $variant->sku_suffix }}">
                                </td>
                                <!-- Height -->
                                <td>
                                    <input type="text"
                                        name="variants[{{ $i }}][height]"
                                        class="form-control"
                                        placeholder="e.g. 10 cm"
                                        value="{{ $variant->height ?? '' }}">
                                </td>

                                <!-- Width -->
                                <td>
                                    <input type="text"
                                        name="variants[{{ $i }}][width]"
                                        class="form-control"
                                        placeholder="e.g. 5 cm"
                                        value="{{ $variant->width ?? '' }}">
                                </td>
                                
                            <td class="text-center">
                                @if($loop->first)
                                    @if($variant->image_url)
                                        @php
                                            $s3Url = 'https://inventorydata-s3-bucket.s3.us-east-1.amazonaws.com/' . $variant->image_url;
                                        @endphp
                                        <img src="{{ $s3Url }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-bottom:5px;">
                                    @else
                                        <span class="badge bg-info">Auto from gallery</span>
                                    @endif
                                    <input type="hidden" name="variants[{{ $i }}][selected_gallery_image]" class="selected-gallery-image" value="{{ $variant->image_url ?? '' }}">
                                @else
                                    @if($variant->image_url)
                                        @php
                                            $s3Url = 'https://inventorydata-s3-bucket.s3.us-east-1.amazonaws.com/' . $variant->image_url;
                                        @endphp
                                        <img src="{{ $s3Url }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-bottom:5px;">
                                    @endif
                                    <div class="d-flex flex-column gap-1">
                                        <input type="file" name="variants[{{ $i }}][image_file]" class="form-control form-control-sm" accept="image/*" style="font-size:12px; padding:4px;">
                                        <small class="text-muted">Choose file</small>
                                    </div>
                                @endif
                            </td>

                                <td>
                                    <input type="number" name="variants[{{ $i }}][sort_order]" class="form-control"
                                        value="{{ $variant->sort_order ?? 0 }}">
                                </td>

                                <!-- Status -->
                                <td>
                                    <select name="variants[{{ $i }}][status]" class="form-control">
                                        <option value="active" {{ $variant->status === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $variant->status === 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </td>

                                <!-- Qty -->
                                <td>
                                    <input type="number" name="variants[{{ $i }}][quantity]" class="form-control qty"
                                        value="{{ $variant->quantity }}" min="0">
                                </td>

                                <!-- Purchase -->
                                <td>
                                    <input type="number" step="0.01" name="variants[{{ $i }}][purchase_price]"
                                        class="form-control purchase" value="{{ $variant->purchase_price }}">
                                </td>
                                <!-- Selling -->
<td>
    <input type="number"
           step="0.01"
           name="variants[{{ $i }}][selling_price]"
           class="form-control selling"
           value="{{ $variant->selling_price ?? 0 }}">
</td>


                                <!-- Total -->
                                <td>
                                    <input type="number" step="0.01" class="form-control total" readonly
                                        value="{{ number_format($variant->quantity * $variant->purchase_price, 2) }}">
                                </td>

                                <!-- Remove -->
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                        ×
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="variantEmptyRow">
                            <td colspan="13" class="text-center text-muted py-4">
                                No variants added yet
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
            <!-- SUMMARY -->
            <div class="border-top bg-light p-3">
                <div class="row g-3">

                    <!-- Gross Total -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <div class="small text-muted mb-1">
                                Gross Total
                            </div>
                            <div class="fs-5 fw-bold">
                                ₹ <span id="grossTotal">0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Commission -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-warning-subtle h-100">
                            <div class="small text-muted mb-1" id="commissionLabel">
                                Supplier Commission
                            </div>
                            <div class="fs-5 fw-bold text-danger">
                                ₹ <span id="commissionAmount">0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Net Payable -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-success-subtle h-100">
                            <div class="small text-muted mb-1">
                                Total
                            </div>
                            <div class="fs-4 fw-bold text-success">
                                ₹ <span id="netPayable">0.00</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>


<template id="variantRowTemplate">
<tr class="variant-row">

    <!-- TYPE -->
    <td>
        <select
    name="variants[__INDEX__][variant_id]"
    class="form-control variant-type">
            <option value="">Select</option>

           @foreach($variants as $masterVariant)
    <option value="{{ $masterVariant->id }}"
        data-input-type="{{ $masterVariant->input_type }}"
        data-has-dimensions="{{ $masterVariant->has_dimensions }}">
    {{ $masterVariant->name }}
</option>

@endforeach

        </select>
    </td>

    <!-- VALUE -->
    <td class="variant-value-cell">
    <select
    name="variants[__INDEX__][variant_value_id]"
    class="form-control variant-value"
    data-selected-id=""
    required>
    <option value="">Select value</option>
</select>


    </td>
<td class="color-cell">
    <input type="color"
       name="variants[__INDEX__][color]"
       class="form-control form-control-color variant-color"
       value="">

    <div class="color-blocker"></div>
</td>



    <!-- SKU -->
    <td>
        <input type="text"
               name="variants[__INDEX__][sku_suffix]"
               class="form-control"
               placeholder="XL-RED">
    </td>

    <!-- Height -->
    <td>
        <input type="text"
               name="variants[__INDEX__][height]"
               class="form-control"
               placeholder="e.g. 10 cm">
    </td>

    <!-- Width -->
    <td>
        <input type="text"
               name="variants[__INDEX__][width]"
               class="form-control"
               placeholder="e.g. 5 cm">
    </td>

   <td class="text-center">
    <input type="hidden" name="variants[__INDEX__][selected_gallery_image]" class="selected-gallery-image">
    <div class="auto-text" style="display:inline-block;">
        <span class="badge bg-info">Auto from gallery</span>
    </div>
    <div class="manual-upload" style="display:none; display:inline-block;">
        <input type="file" name="variants[__INDEX__][image_file]" class="form-control form-control-sm variant-image-file" accept="image/*" style="display:none;">
        <button type="button" class="btn btn-sm btn-outline-secondary choose-file-btn">Choose File</button>
    </div>
</td>
    <!-- Sort -->
    <td>
        <input type="number"
               name="variants[__INDEX__][sort_order]"
               class="form-control"
               value="0">
    </td>

    <!-- Status -->
    <td>
        <select name="variants[__INDEX__][status]" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </td>

    <!-- Qty -->
    <td>
        <input type="number"
               name="variants[__INDEX__][quantity]"
               class="form-control qty"
               value="0" min="0">
    </td>

    <!-- Purchase -->
    <td>
        <input type="number"
               step="0.01"
               name="variants[__INDEX__][purchase_price]"
               class="form-control purchase"
               value="0">
    </td>
    <!-- Selling -->
<td>
    <input type="number"
           step="0.01"
           name="variants[__INDEX__][selling_price]"
           class="form-control selling"
           value="0">
</td>


    <!-- Total -->
    <td>
        <input type="number"
               step="0.01"
               class="form-control total"
               readonly value="0">
    </td>

    <!-- Remove -->
    <td class="text-center">
        <button type="button"
                class="btn btn-sm btn-outline-danger remove-variant">
            ×
        </button>
    </td>

</tr>
</template>
