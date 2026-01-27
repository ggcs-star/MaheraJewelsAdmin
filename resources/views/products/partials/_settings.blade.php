<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">⚙️</span>
        <span>Product Settings</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Sort Order -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Sort Order
                </label>
                <input type="number"
                       name="sort_order"
                       class="form-control"
                       value="0"
                       min="0">
                <div class="form-text">
                    Lower value appears first.
                </div>
            </div>

            <!-- Featured -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Featured
                </label>
                <select name="is_featured" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
                <div class="form-text">
                    Show on homepage featured section.
                </div>
            </div>

            <!-- Top Selling -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Top Selling
                </label>
                <select name="is_top_selling" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
                <div class="form-text">
                    Highlight as best seller.
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Status <span class="text-danger">*</span>
                </label>
                <select name="status"
                        class="form-select"
                        required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="form-text">
                    Control product availability.
                </div>
            </div>

            <!-- Visibility -->
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    Visibility <span class="text-danger">*</span>
                </label>
                <select name="visibility"
                        class="form-select"
                        required>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
                <div class="form-text">
                    Public = visible to users.
                </div>
            </div>

        </div>
    </div>
</div>
