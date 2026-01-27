@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🖼️</span>
        <span>Product Image</span>
    </div>

    <div class="card-body">
        <div class="row g-4 align-items-center">

            <!-- Image Upload -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Product Image
                </label>

                <input type="file"
                       name="image_url"
                       id="imageInput"
                       class="form-control"
                       accept="image/*">

                <div class="form-text">
                    JPG, PNG, WEBP • Max 2MB • Recommended square image
                </div>
            </div>

            <!-- Preview Box -->
            <div class="col-md-8">
                <label class="form-label fw-semibold">
                    Preview
                </label>

                <div id="imagePreview"
                     class="border rounded d-flex align-items-center justify-content-center bg-light"
                     style="width:100%; height:220px; cursor:pointer;">

                    @if(!empty($product?->image_url))
                        {{-- EDIT MODE: show existing image --}}
                        <img src="{{ asset('storage/'.$product->image_url) }}"
                             class="img-fluid rounded"
                             style="max-height:100%; object-fit:contain;">
                    @else
                        {{-- CREATE MODE --}}
                        <div class="text-center text-muted" id="imagePlaceholder">
                            <div class="fs-3">📷</div>
                            <div class="small">
                                Click to upload image
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
