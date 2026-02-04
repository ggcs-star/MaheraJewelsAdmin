<div class="mb-3">
    <label class="form-label">Tax Name</label>
    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $tax->name ?? '') }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label">Rate</label>
    <input type="number"
           step="0.01"
           name="rate"
           class="form-control"
           value="{{ old('rate', $tax->rate ?? '') }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-control" required>
        <option value="percentage"
            {{ old('type', $tax->type ?? '') === 'percentage' ? 'selected' : '' }}>
            Percentage (%)
        </option>
        <option value="fixed"
            {{ old('type', $tax->type ?? '') === 'fixed' ? 'selected' : '' }}>
            Fixed Amount
        </option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control" required>
        <option value="active"
            {{ old('status', $tax->status ?? '') === 'active' ? 'selected' : '' }}>
            Active
        </option>
        <option value="inactive"
            {{ old('status', $tax->status ?? '') === 'inactive' ? 'selected' : '' }}>
            Inactive
        </option>
    </select>
</div>
