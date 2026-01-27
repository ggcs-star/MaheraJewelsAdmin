<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0 d-flex align-items-center">
            <i class="fas fa-boxes text-primary me-2"></i>
            Product Variants
        </h6>
        <button type="button"
                id="addVariantBtn"
                class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add Variant
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
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Qty</th>
                        <th>Purchase</th>
                        <th>Total</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="variantEmptyRow">
                        <td colspan="10" class="text-center text-muted py-4">
                            No variants added yet
                        </td>
                    </tr>
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
        <td><input type="text" name="variants[__INDEX__][variant_type]" class="form-control" required></td>
        <td><input type="text" name="variants[__INDEX__][variant_value]" class="form-control" required></td>
        <td><input type="text" name="variants[__INDEX__][sku_suffix]" class="form-control"></td>
        <td><input type="file" name="variants[__INDEX__][image_url]" class="form-control" accept="image/*"></td>
        <td><input type="number" name="variants[__INDEX__][sort_order]" class="form-control" value="0"></td>
        <td>
            <select name="variants[__INDEX__][status]" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </td>
        <td><input type="number" name="variants[__INDEX__][quantity]" class="form-control qty" min="0" value="0"></td>
        <td><input type="number" step="0.01" name="variants[__INDEX__][purchase_price]" class="form-control purchase">
        </td>
        <td><input type="number" step="0.01" class="form-control total" readonly value="0"></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-variant">×</button>
        </td>
    </tr>
</template>