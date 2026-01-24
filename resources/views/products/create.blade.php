@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Create Product</h2>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ admin_route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- BASIC INFO --}}
            <div class="card mb-4">
                <div class="card-header"><strong>Basic Information</strong></div>
                <div class="card-body row">

                    <div class="col-md-4 mb-3">
                        <label>SKU *</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Slug *</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control"
                            rows="2">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- CATEGORY / SUPPLIER --}}
            <div class="card mb-4">
                <div class="card-header"><strong>Category & Supplier</strong></div>
                <div class="card-body row">

                    <div class="col-md-4 mb-3">
                        <label>Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">Select</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                    </div>

                </div>
            </div>

            {{-- PRICING --}}
            <div class="card mb-4">
                <div class="card-header"><strong>Pricing</strong></div>
                <div class="card-body row">

                    <div class="col-md-4 mb-3">
                        <label>Cost Price *</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                            value="{{ old('cost_price') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Selling Price *</label>
                        <input type="number" step="0.01" name="base_selling_price" class="form-control"
                            value="{{ old('base_selling_price') }}" required>
                    </div>

                </div>
            </div>

            {{-- IMAGES --}}
            <div class="card mb-4">
    <div class="card-header"><strong>Images</strong></div>
    <div class="card-body">

        {{-- Main Image --}}
        <div class="mb-4">
            <label class="form-label">Main Image</label>
            <input type="file" name="image_url" class="form-control" accept="image/*">
        </div>

        {{-- Gallery --}}
        <div class="mb-2 d-flex align-items-center justify-content-between">
            <label class="form-label mb-0">Gallery Images</label>

            {{-- + Button --}}
            <button type="button"
                    class="btn btn-sm btn-outline-primary"
                    id="addGalleryBtn">
                + Add Image
            </button>
        </div>

        {{-- Hidden File Input --}}
        <input type="file"
               id="galleryInput"
               name="gallery_images[]"
               class="d-none"
               accept="image/*">

        {{-- Preview --}}
        <div id="galleryPreview"
             class="d-flex flex-wrap gap-3 mt-3">
        </div>

    </div>
</div>



            {{-- SETTINGS --}}
            <div class="card mb-4">
                <div class="card-header"><strong>Settings</strong></div>
                <div class="card-body row">

                    <div class="col-md-3 mb-3">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Featured</label>
                        <select name="is_featured" class="form-control">
                            <option value="0">No</option>
                            <option value="1" {{ old('is_featured') == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Top Selling</label>
                        <select name="is_top_selling" class="form-control">
                            <option value="0">No</option>
                            <option value="1" {{ old('is_top_selling') == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Visibility *</label>
                        <select name="visibility" class="form-control" required>
                            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="text-end">
                <a href="{{ admin_route('products.index') }}" class="btn btn-secondary">
                    Back
                </a>
                <button class="btn btn-primary">
                    Save Product
                </button>
            </div>

        </form>
    </div>
 <script>
    const addGalleryBtn = document.getElementById('addGalleryBtn');
    const galleryInput  = document.getElementById('galleryInput');
    const galleryPreview = document.getElementById('galleryPreview');

    let selectedFiles = [];

    // + button click → open file dialog
    addGalleryBtn.addEventListener('click', () => {
        galleryInput.click();
    });

    // when image selected
    galleryInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);

        files.forEach(file => {
            // avoid duplicate files
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });

        updateInputFiles();
        renderPreview();

        // reset input so same file can be re-selected
        galleryInput.value = '';
    });

    function renderPreview() {
        galleryPreview.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const div = document.createElement('div');
                div.classList.add('position-relative');

                div.innerHTML = `
                    <img src="${e.target.result}"
                         class="img-thumbnail"
                         style="width:120px;height:120px;object-fit:cover;">

                    <button type="button"
                            class="btn btn-danger btn-sm position-absolute top-0 end-0"
                            style="border-radius:50%;"
                            onclick="removeGalleryImage(${index})">
                        ×
                    </button>
                `;

                galleryPreview.appendChild(div);
            };

            reader.readAsDataURL(file);
        });
    }

    function removeGalleryImage(index) {
        selectedFiles.splice(index, 1);
        updateInputFiles();
        renderPreview();
    }

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        galleryInput.files = dataTransfer.files;
    }
</script>


@endsection

