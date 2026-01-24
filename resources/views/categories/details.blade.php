@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1">Category Details</h2>
            <div class="text-muted">Category ID: #{{ str_pad($category->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('categories.edit', $category) }}" class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i>Edit Category
            </a>
            <a href="{{ admin_route('categories.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    {{-- QUICK STATS CARDS WITH ICONS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-{{ $category->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-{{ $category->status === 'active' ? 'check-circle' : 'pause-circle' }} text-{{ $category->status === 'active' ? 'success' : 'secondary' }} fa-lg"></i>
                        </div>
                        <div class="text-muted small">Status</div>
                    </div>
                    <div class="fw-bold fs-4">{{ ucfirst($category->status) }}</div>
                    <div class="small text-muted mt-1">Category status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-eye text-primary fa-lg"></i>
                        </div>
                        <div class="text-muted small">Visibility</div>
                    </div>
                    <div class="fw-bold fs-4 text-capitalize">{{ $category->visibility }}</div>
                    <div class="small text-muted mt-1">Visibility status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-sitemap text-info fa-lg"></i>
                        </div>
                        <div class="text-muted small">Sub Categories</div>
                    </div>
                    <div class="fw-bold fs-4">{{ $category->children->count() }}</div>
                    <div class="small text-muted mt-1">Child categories</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-star text-warning fa-lg"></i>
                        </div>
                        <div class="text-muted small">Featured</div>
                    </div>
                    <div class="fw-bold fs-4">
                        {{ $category->is_featured ? 'Yes' : 'No' }}
                    </div>
                    <div class="small text-muted mt-1">Featured status</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- LEFT PROFILE CARD --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-folder text-primary me-2"></i>
                        Category Overview
                    </h5>
                </div>
                <div class="card-body text-center pt-0">

                  @if($category->image_url)
    <div class="mb-3 border rounded overflow-hidden" style="height: 140px;">
        <img
            src="{{ asset('storage/' . $category->image_url) }}"
            class="img-fluid h-100 w-100 object-fit-cover"
            alt="{{ $category->name }}"
        >
    </div>

                    @else
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 100px; height: 100px; font-size: 36px; font-weight: 600;">
                                {{ strtoupper(substr($category->name,0,1)) }}
                            </div>
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                                <div class="rounded-circle bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}"
                                     style="width: 16px; height: 16px;"></div>
                            </div>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">{{ $category->name }}</h4>

                    <div class="text-muted mb-3">
                        <i class="fas fa-hashtag me-1"></i>Slug: {{ $category->slug }}
                    </div>

                    <div class="mb-4">
                        <span class="badge rounded-pill px-4 py-2 fs-6
                            {{ $category->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            <i class="fas fa-{{ $category->status === 'active' ? 'check' : 'pause' }} me-1"></i>
                            {{ ucfirst($category->status) }}
                        </span>
                    </div>

                    {{-- QUICK INFO --}}
                    <div class="border-top pt-4 mt-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-sitemap text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Parent Category</div>
                                <div class="fw-semibold">{{ $category->parent?->name ?? 'Root Category' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-sort-numeric-up text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Sort Order</div>
                                <div class="fw-semibold">{{ $category->sort_order ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT DETAILS --}}
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
                                <div class="col-md-4">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-tag text-muted me-2 small"></i>
                                            <div class="text-muted small">Category Name</div>
                                        </div>
                                        <div class="fw-semibold">{{ $category->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-hashtag text-muted me-2 small"></i>
                                            <div class="text-muted small">Slug</div>
                                        </div>
                                        <div class="fw-semibold">{{ $category->slug }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-level-up-alt text-muted me-2 small"></i>
                                            <div class="text-muted small">Parent Category</div>
                                        </div>
                                        <div class="fw-semibold">
                                            {{ $category->parent?->name ?? 'Root Category' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION CARD --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-align-left text-primary me-2"></i>
                                Description
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-4 border rounded bg-light bg-opacity-50">
                                @if($category->description)
                                    <div class="d-flex">
                                        <i class="fas fa-quote-left text-muted me-2 mt-1"></i>
                                        <div>{{ $category->description }}</div>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle fa-lg me-2"></i>
                                        No description added
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO INFORMATION CARD --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-search text-primary me-2"></i>
                                SEO Information
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-heading text-muted me-2 small"></i>
                                        <span class="text-muted small">Meta Title</span>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary small">
                                        <i class="fas fa-searchengin me-1"></i>SEO
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-primary ps-3">
                                    {{ $category->meta_title ?? '—' }}
                                </div>
                            </div>

                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-alt text-muted me-2 small"></i>
                                        <span class="text-muted small">Meta Description</span>
                                    </div>
                                    <span class="badge bg-info bg-opacity-10 text-info small">
                                        <i class="fas fa-search me-1"></i>Meta
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-info ps-3">
                                    {{ $category->meta_description ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HIERARCHY & SETTINGS CARD --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-cog text-primary me-2"></i>
                                Settings & Hierarchy
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-sort-numeric-up text-muted me-2 small"></i>
                                            <div class="text-muted small">Sort Order</div>
                                        </div>
                                        <div class="fw-semibold">
                                            <span class="badge bg-secondary">{{ $category->sort_order ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-star text-muted me-2 small"></i>
                                            <div class="text-muted small">Featured</div>
                                        </div>
                                        <div class="fw-semibold">
                                            @if($category->is_featured)
                                                <span class="badge bg-warning text-white">
                                                    <i class="fas fa-star me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-star me-1"></i>No
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-sitemap text-muted me-2 small"></i>
                                            <div class="text-muted small">Sub Categories</div>
                                        </div>
                                        <div class="fw-semibold">
                                            <span class="badge bg-info text-white">{{ $category->children->count() }}</span>
                                        </div>
                                        @if($category->children->count())
                                            <div class="mt-2 ps-3">
                                                @foreach($category->children->take(3) as $child)
                                                    <span class="badge bg-light text-dark me-1 mb-1">
                                                        <i class="fas fa-folder me-1"></i>{{ $child->name }}
                                                    </span>
                                                @endforeach
                                                @if($category->children->count() > 3)
                                                    <span class="badge bg-light text-dark">+{{ $category->children->count() - 3 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- META KEYWORDS CARD --}}
                @if($category->meta_keywords)
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-key text-primary me-2"></i>
                                Meta Keywords
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-3 border rounded bg-light">
                                <div class="fw-semibold">
                                    @foreach(explode(',', $category->meta_keywords) as $keyword)
                                        <span class="badge bg-primary bg-opacity-10 text-primary me-1 mb-1">
                                            <i class="fas fa-hashtag me-1"></i>{{ trim($keyword) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>

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
.border-start {
    border-left-width: 4px !important;
}
.small {
    font-size: 0.85rem;
}
.object-fit-cover {
    object-fit: cover;
}
</style>
@endsection