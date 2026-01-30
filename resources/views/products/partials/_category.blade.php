<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🗂️</span>
        <span>Category</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Main Category -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                     Category <span class="text-danger">*</span>
                </label>
                <select id="mainCategory" class="form-select">
                    <option value="">Select  category</option>
                    @foreach ($categories->whereNull('parent_id') as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <div class="form-text">
                    Choose the primary category for this product.
                </div>
            </div>

            <!-- Sub Category -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Sub Category
                </label>
                <select id="subCategory" class="form-select" disabled>
                    <option value="">Select sub category</option>
                </select>
                <div class="form-text">
                    Available after selecting main category.
                </div>
            </div>

            <!-- Hidden final category -->
            <input type="hidden"
       name="category_id"
       id="finalCategoryId"
       value="{{ old('category_id', $product->category_id ?? '') }}">


            <!-- Brand -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Brand
                </label>

                <input type="text"
                       name="brand"
                       class="form-control"
                       placeholder="e.g. Samsung, Nike"
                       value="{{ old('brand', $product->brand ?? '') }}">

                <div class="form-text">
                    Optional – helps with filtering & search.
                </div>
            </div>

            <!-- Hidden final category -->
            <!-- <input type="hidden"
                   name="category_id"
                   id="finalCategoryId"
                   value="{{ old('category_id', $product->category_id ?? '') }}"> -->

        </div>
    </div>
</div>
