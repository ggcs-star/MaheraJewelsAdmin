@php
    /** @var \App\Models\Product|null $product */
@endphp

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
        <span class="text-primary fs-5">🖼️</span>
        <span>Product Images</span>
    </div>

    <div class="card-body">
        <div class="row g-4 align-items-center">

            <!-- Image Upload -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Product Images
                </label>

                <input type="file"
                       name="gallery_images[]"
                       id="imageInput"
                       class="form-control"
                       accept="image/*"
                       multiple>

                <div class="form-text">
                    JPG, PNG, WEBP • Max 2MB each • Recommended square images
                </div>
            </div>

            <!-- Preview Box -->
            <div class="col-md-8">
                <label class="form-label fw-semibold">
                    Preview
                </label>

                <div id="imagePreview"
                     class="border rounded bg-light p-2 d-flex flex-wrap gap-2 align-items-start"
                     style="width:100%; min-height:220px; cursor:pointer;">

            @if(!empty($product?->gallery_images) && is_array($product->gallery_images))
                @foreach($product->gallery_images as $img)
                    <img
                        src="{{ \App\Helpers\S3Helper::url($img) }}"
                        data-path="{{ $img }}"
                        class="rounded border selectable-gallery-image"
                        style="width:100px;height:100px;object-fit:cover;cursor:pointer;">
                @endforeach

            @elseif(!empty($product?->image_url))
                <img
                    src="{{ \App\Helpers\S3Helper::url($product->image_url) }}"
                    data-path="{{ $product->image_url }}"
                    class="rounded border selectable-gallery-image"
                    style="width:100px;height:100px;object-fit:cover;cursor:pointer;">


            @else
                <div class="text-center text-muted w-100" id="imagePlaceholder">
                    <div class="fs-3">📷</div>
                    <div class="small">
                        Click to upload images
                    </div>
                </div>
            @endif


                </div>
            </div>

        </div>
    </div>
</div>
