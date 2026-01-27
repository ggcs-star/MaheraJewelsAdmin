<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🖼️</span>
        <span>Product Images</span>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Main Image -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Main Product Image
                </label>

                <input type="file"
                       name="image_url"
                       class="form-control"
                       accept="image/*">

                <div class="form-text">
                    JPG, PNG, WEBP • Max 2MB • Recommended 1:1 ratio
                </div>
            </div>

            <!-- Preview -->
            <div class="col-md-8">
                <label class="form-label fw-semibold">
                    Preview
                </label>

                <div id="galleryPreview"
                     class="border rounded bg-light p-2 d-flex flex-wrap gap-2"
                     style="min-height:120px;">

                    <div class="text-muted small">
                        Selected image preview will appear here
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
