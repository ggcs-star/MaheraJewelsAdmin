@php
    /** @var \App\Models\Product|null $product */
@endphp

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
                       min="0"
                       value="{{ old('sort_order', $product->sort_order ?? 0) }}">
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
                    <option value="0"
                        {{ old('is_featured', $product->is_featured ?? 0) == 0 ? 'selected' : '' }}>
                        No
                    </option>
                    <option value="1"
                        {{ old('is_featured', $product->is_featured ?? 0) == 1 ? 'selected' : '' }}>
                        Yes
                    </option>
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
                    <option value="0"
                        {{ old('is_top_selling', $product->is_top_selling ?? 0) == 0 ? 'selected' : '' }}>
                        No
                    </option>
                    <option value="1"
                        {{ old('is_top_selling', $product->is_top_selling ?? 0) == 1 ? 'selected' : '' }}>
                        Yes
                    </option>
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
                    <option value="active"
                        {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive"
                        {{ old('status', $product->status ?? 'active') === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
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
                    <option value="public"
                        {{ old('visibility', $product->visibility ?? 'public') === 'public' ? 'selected' : '' }}>
                        Public
                    </option>
                    <option value="private"
                        {{ old('visibility', $product->visibility ?? 'public') === 'private' ? 'selected' : '' }}>
                        Private
                    </option>
                </select>
                <div class="form-text">
                    Public = visible to users.
                </div>
            </div>

        </div>
    </div>
</div>
