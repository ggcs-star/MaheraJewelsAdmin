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
                        <th>SKU</th>
                        <th>Image</th>
                        <th style="width:70px;text-align:center;">Sort</th>
                        <th style="width:120px;text-align:center;">Status</th>
                        <th style="width:80px;text-align:center;">Qty</th>

                        <th>Purchase</th>
                        <th>Total</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($product) && $product->variants->count())
                        @foreach($product->variants as $i => $variant)
                            <tr>
                                <!-- Type -->
                                <td>
                                    <input type="text" name="variants[{{ $i }}][variant_type]" class="form-control"
                                        value="{{ $variant->variant_type }}" required>
                                </td>

                                <!-- Value -->
                                <td>
                                    <input type="text" name="variants[{{ $i }}][variant_value]" class="form-control"
                                        value="{{ $variant->variant_value }}" required>
                                </td>

                                <!-- SKU -->
                                <td>
                                    <input type="text" name="variants[{{ $i }}][sku_suffix]" class="form-control"
                                        value="{{ $variant->sku_suffix }}">
                                </td>

                                <!-- Image -->
                                <td>
                                    <input type="file" name="variants[{{ $i }}][image_url]" class="form-control"
                                        accept="image/*">
                                </td>

                                <!-- Sort -->
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
                            <td colspan="10" class="text-center text-muted py-4">
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
    <tr>
        <!-- Variant Type -->
        <td>
            <input type="text" name="variants[__INDEX__][variant_type]" class="form-control" placeholder="red/kg"
                required>
        </td>

        <!-- Variant Value -->
        <td>
            <input type="text" name="variants[__INDEX__][variant_value]" class="form-control" placeholder="XL/230g"
                required>
        </td>

        <!-- SKU Suffix -->
        <td>
            <input type="text" name="variants[__INDEX__][sku_suffix]" class="form-control" placeholder="XL-RED">
        </td>

        <!-- Variant Image -->
        <td>
            <input type="file" name="variants[__INDEX__][image_url]" class="form-control" accept="image/*">
        </td>

        <!-- Sort Order -->
        <td>
            <input type="number" name="variants[__INDEX__][sort_order]" class="form-control" placeholder="0" value="0">
        </td>

        <!-- Status -->
        <td>
            <select name="variants[__INDEX__][status]" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </td>

        <!-- Quantity -->
        <td>
            <input type="number" name="variants[__INDEX__][quantity]" class="form-control qty" placeholder="0" min="0"
                value="0">
        </td>

        <!-- Purchase Price -->
        <td>
            <input type="number" step="0.01" name="variants[__INDEX__][purchase_price]" class="form-control purchase"
                placeholder="0.00">
        </td>

        <!-- Total (Auto Calculated) -->
        <td>
            <input type="number" step="0.01" class="form-control total" placeholder="0.00" readonly value="0">
        </td>

        <!-- Remove -->
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-variant" title="Remove variant">
                ×
            </button>
        </td>
    </tr>
</template>