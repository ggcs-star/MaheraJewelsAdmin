@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('products.index') }}">Inventory</a></li>
                    <li class="breadcrumb-item active">Add New Inventory</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1">Add New Inventory</h2>
            <div class="text-muted">Create a new product with variants and details</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('products.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Inventory
            </a>
        </div>
    </div>

    @include('products.partials._errors')

    <form action="{{ admin_route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="row g-4">

            {{-- LEFT SIDEBAR CARD --}}
            <div class="col-xl-4 col-lg-4">
                <div class="card shadow-lg border-0 h-100 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-cube text-primary me-2"></i>
                            Product Overview
                        </h5>
                    </div>
                    <div class="card-body pt-0">

                        {{-- Image Upload Section --}}
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block mb-3">
                                <div id="imagePreview" class="rounded border overflow-hidden" style="width: 200px; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <div class="small">No Image</div>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm">
                                    <label for="imageInput" class="btn btn-sm btn-primary mb-0" style="cursor: pointer;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="image_url" id="imageInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                JPG, PNG, WEBP • Max 2MB
                            </div>
                        </div>

                        {{-- Quick Info Cards --}}
                        <div class="border-top pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted mb-2">
                                    <i class="fas fa-barcode me-1"></i>SKU <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="sku" class="form-control form-control-sm" 
                                       placeholder="PROD-001" value="{{ old('sku') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted mb-2">
                                    <i class="fas fa-hashtag me-1"></i>Slug <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="slug" class="form-control form-control-sm" 
                                       placeholder="product-slug" value="{{ old('slug') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>Brand
                                </label>
                                <input type="text" name="brand" class="form-control form-control-sm" 
                                       placeholder="Brand Name" value="{{ old('brand') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted mb-2">
                                    <i class="fas fa-eye me-1"></i>Visibility <span class="text-danger">*</span>
                                </label>
                                <select name="visibility" class="form-select form-select-sm" required>
                                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted mb-2">
                                    <i class="fas fa-power-off me-1"></i>Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="border-top pt-4 mt-3">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-save me-2"></i>Save Inventory
                            </button>
                            <a href="{{ admin_route('products.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT DETAILS SECTION --}}
            <div class="col-xl-8 col-lg-8">
                <div class="row g-4">

                    {{-- BASIC INFORMATION CARD --}}
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                                <h6 class="fw-bold mb-0 d-flex align-items-center">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Basic Information
                                </h6>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-address-card me-1"></i>Details
                                </span>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">
                                            Product Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control" 
                                               placeholder="Enter product name" value="{{ old('name') }}" required>
                                        <div class="form-text">This name will be visible to customers</div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Short Description</label>
                                        <textarea name="short_description" class="form-control" rows="2" 
                                                  placeholder="One-line summary of the product">{{ old('short_description') }}</textarea>
                                        <div class="form-text">Displayed in product listings</div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Description</label>
                                        <textarea name="description" class="form-control" rows="4" 
                                                  placeholder="Detailed product description">{{ old('description') }}</textarea>
                                        <div class="form-text">Shown on the product detail page</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CATEGORY & SUPPLIER CARD --}}
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                                <h6 class="fw-bold mb-0 d-flex align-items-center">
                                    <i class="fas fa-sitemap text-primary me-2"></i>
                                    Category & Supplier
                                </h6>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        @include('products.partials._category')
                                    </div>
                                    <div class="col-md-6">
                                        @include('products.partials._supplier')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VARIANTS TABLE CARD --}}
                    <div class="col-12">
                        @include('products.partials._variants')
                    </div>

                    {{-- SEO SETTINGS CARD --}}
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                                <h6 class="fw-bold mb-0 d-flex align-items-center">
                                    <i class="fas fa-search text-primary me-2"></i>
                                    SEO Settings
                                </h6>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control" 
                                               placeholder="SEO meta title" value="{{ old('meta_title') }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" class="form-control" 
                                               placeholder="keyword1, keyword2, keyword3" value="{{ old('meta_keywords') }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Meta Description</label>
                                        <textarea name="meta_description" class="form-control" rows="2" 
                                                  placeholder="SEO meta description">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ADDITIONAL SETTINGS CARD --}}
                    <div class="col-12">
                        @include('products.partials._settings')
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    border-color: #e0e0e0;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.sticky-top {
    position: sticky;
}
#imagePreview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection

@section('scripts')
    @include('products.scripts.gallery-js')
    @include('products.scripts.variants-js')
    @include('products.scripts.categories-js')
    @include('products.scripts.supplier-js')
@endsection
