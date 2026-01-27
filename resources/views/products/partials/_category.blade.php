<div>
    <label class="form-label fw-semibold">
        <i class="fas fa-folder me-1"></i>Category <span class="text-danger">*</span>
    </label>
    <select id="mainCategory" class="form-select">
        <option value="">Select category</option>
        @foreach ($categories->whereNull('parent_id') as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    <div class="form-text small">Choose the primary category</div>

    <label class="form-label fw-semibold mt-3">
        <i class="fas fa-sitemap me-1"></i>Sub Category
    </label>
    <select id="subCategory" class="form-select" disabled>
        <option value="">Select sub category</option>
    </select>
    <div class="form-text small">Available after selecting main category</div>

    <!-- Hidden final category -->
    <input type="hidden" name="category_id" id="finalCategoryId">
</div>
