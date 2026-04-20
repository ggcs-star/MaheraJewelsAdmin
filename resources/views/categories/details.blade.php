@extends('layouts.admin')

@section('content')
<div class="container-fluid category-page px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}" style="color: var(--primary);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('categories.index') }}" style="color: var(--primary);">Categories</a></li>
                    <li class="breadcrumb-item active" style="color: #64748b;">{{ $category->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1" style="color: #1e293b;">Category Details</h2>
            <div class="text-muted" style="font-size: 13px;">Serial No: {{ $serial }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('categories.edit', $category) }}" class="btn px-4" style="background: var(--primary); color: white; border: none; border-radius: 8px; padding: 8px 20px;">
                <i class="fas fa-edit me-2"></i>Edit Category
            </a>
            <a href="{{ admin_route('categories.index') }}" class="btn px-4" style="background: #f1f5f9; color: #64748b; border: none; border-radius: 8px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2 rounded me-2" style="background: {{ $category->status === 'active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(100, 116, 139, 0.1)' }};">
                            <i class="fas fa-{{ $category->status === 'active' ? 'check-circle' : 'pause-circle' }} fa-lg" style="color: {{ $category->status === 'active' ? '#10b981' : '#64748b' }};"></i>
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">Status</div>
                    </div>
                    <div class="fw-bold fs-4" style="color: #1e293b;">{{ ucfirst($category->status) }}</div>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Category status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2 rounded me-2" style="background: rgba(68, 12, 44, 0.1);">
                            <i class="fas fa-eye fa-lg" style="color: var(--primary);"></i>
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">Visibility</div>
                    </div>
                    <div class="fw-bold fs-4 text-capitalize" style="color: #1e293b;">{{ $category->visibility }}</div>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Visibility status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2 rounded me-2" style="background: rgba(14, 165, 233, 0.1);">
                            <i class="fas fa-sitemap fa-lg" style="color: #0ea5e9;"></i>
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">Sub Categories</div>
                    </div>
                    <div class="fw-bold fs-4" style="color: #1e293b;">{{ $category->children->count() }}</div>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Child categories</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="p-2 rounded me-2" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-star fa-lg" style="color: #f59e0b;"></i>
                        </div>
                        <div class="text-muted small" style="font-size: 11px;">Featured</div>
                    </div>
                    <div class="fw-bold fs-4" style="color: #1e293b;">{{ $category->is_featured ? 'Yes' : 'No' }}</div>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Featured status</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                        <i class="fas fa-folder me-2" style="color: var(--primary);"></i>
                        Category Overview
                    </h5>
                </div>
                <div class="card-body text-center pt-0">
                    @if($category->image_url)
                        <div class="mb-3 border rounded-lg overflow-hidden" style="height: 140px; border-radius: 8px;">
                            <img src="{{ $category->image_url }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $category->name }}">
                        </div>
                    @else
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 100px; height: 100px; font-size: 36px; font-weight: 600; background: var(--primary); color: white;">
                                {{ strtoupper(substr($category->name,0,1)) }}
                            </div>
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                                <div class="rounded-circle" style="width: 16px; height: 16px; background: {{ $category->status === 'active' ? '#10b981' : '#64748b' }};"></div>
                            </div>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1" style="color: #1e293b;">{{ $category->name }}</h4>
                    <div class="text-muted mb-3" style="font-size: 13px;">
                        <i class="fas fa-hashtag me-1"></i>Slug: {{ $category->slug }}
                    </div>

                    <div class="mb-4">
                        <span class="badge rounded-pill px-4 py-2 fs-6" style="background: {{ $category->status === 'active' ? '#10b981' : '#64748b' }}; color: white;">
                            <i class="fas fa-{{ $category->status === 'active' ? 'check' : 'pause' }} me-1"></i>
                            {{ ucfirst($category->status) }}
                        </span>
                    </div>

                    <div class="border-top pt-4 mt-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-2 me-3" style="background: rgba(68, 12, 44, 0.1);">
                                <i class="fas fa-sitemap" style="color: var(--primary);"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small" style="font-size: 11px;">Parent Category</div>
                                <div class="fw-semibold" style="color: #1e293b;">{{ $category->parent?->name ?? 'Root Category' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-3" style="background: rgba(68, 12, 44, 0.1);">
                                <i class="fas fa-sort-numeric-up" style="color: var(--primary);"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small" style="font-size: 11px;">Sort Order</div>
                                <div class="fw-semibold" style="color: #1e293b;">{{ $category->sort_order ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-info-circle me-2" style="color: var(--primary);"></i>
                                Basic Information
                            </h6>
                            <span class="badge px-2 py-1" style="background: #f1f5f9; color: #64748b; font-size: 10px;">
                                <i class="fas fa-address-card me-1"></i>Details
                            </span>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-tag text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Category Name</div>
                                        </div>
                                        <div class="fw-semibold" style="color: #1e293b;">{{ $category->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-hashtag text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Slug</div>
                                        </div>
                                        <div class="fw-semibold" style="color: #1e293b;">{{ $category->slug }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-level-up-alt text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Parent Category</div>
                                        </div>
                                        <div class="fw-semibold" style="color: #1e293b;">{{ $category->parent?->name ?? 'Root Category' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-align-left me-2" style="color: var(--primary);"></i>
                                Description
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-4 border rounded-lg" style="border: 1px solid #e2e8f0; background: #f8fafc;">
                                @if($category->description)
                                    <div class="d-flex">
                                        <i class="fas fa-quote-left text-muted me-2 mt-1"></i>
                                        <div style="color: #475569;">{{ $category->description }}</div>
                                    </div>
                                @else
                                    <div class="text-center py-3" style="color: #94a3b8;">
                                        <i class="fas fa-info-circle fa-lg me-2"></i>
                                        No description added
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-search me-2" style="color: var(--primary);"></i>
                                SEO Information
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-heading text-muted me-2 small"></i>
                                        <span class="text-muted small" style="font-size: 11px;">Meta Title</span>
                                    </div>
                                    <span class="badge px-2 py-1" style="background: rgba(68, 12, 44, 0.1); color: var(--primary); font-size: 10px;">
                                        <i class="fas fa-searchengin me-1"></i>SEO
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 ps-3" style="border-left: 3px solid var(--primary); color: #1e293b;">
                                    {{ $category->meta_title ?? '—' }}
                                </div>
                            </div>

                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-alt text-muted me-2 small"></i>
                                        <span class="text-muted small" style="font-size: 11px;">Meta Description</span>
                                    </div>
                                    <span class="badge px-2 py-1" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9; font-size: 10px;">
                                        <i class="fas fa-search me-1"></i>Meta
                                    </span>
                                </div>
                                <div class="fw-semibold p-2 ps-3" style="border-left: 3px solid #0ea5e9; color: #1e293b;">
                                    {{ $category->meta_description ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-cog me-2" style="color: var(--primary);"></i>
                                Settings & Hierarchy
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-sort-numeric-up text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Sort Order</div>
                                        </div>
                                        <div class="fw-semibold">
                                            <span class="badge px-2 py-1" style="background: #e2e8f0; color: #475569;">{{ $category->sort_order ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-star text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Featured</div>
                                        </div>
                                        <div class="fw-semibold">
                                            @if($category->is_featured)
                                                <span class="badge px-2 py-1" style="background: #f59e0b; color: white;">
                                                    <i class="fas fa-star me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="badge px-2 py-1" style="background: #e2e8f0; color: #475569;">
                                                    <i class="far fa-star me-1"></i>No
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-sitemap text-muted me-2 small"></i>
                                            <div class="text-muted small" style="font-size: 11px;">Sub Categories</div>
                                        </div>
                                        <div class="fw-semibold">
                                            <span class="badge px-2 py-1" style="background: #0ea5e9; color: white;">{{ $category->children->count() }}</span>
                                        </div>
                                        @if($category->children->count())
                                            <div class="mt-2 ps-3">
                                                @foreach($category->children->take(3) as $child)
                                                    <span class="badge me-1 mb-1" style="background: #e2e8f0; color: #475569;">
                                                        <i class="fas fa-folder me-1"></i>{{ $child->name }}
                                                    </span>
                                                @endforeach
                                                @if($category->children->count() > 3)
                                                    <span class="badge" style="background: #e2e8f0; color: #475569;">+{{ $category->children->count() - 3 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($category->meta_keywords)
                <div class="col-12">
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-key me-2" style="color: var(--primary);"></i>
                                Meta Keywords
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-3 border rounded-lg" style="border: 1px solid #e2e8f0; background: #f8fafc;">
                                <div class="fw-semibold">
                                    @foreach(explode(',', $category->meta_keywords) as $keyword)
                                        <span class="badge me-1 mb-1" style="background: rgba(68, 12, 44, 0.1); color: var(--primary);">
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
    .hover-card:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .rounded-lg {
        border-radius: 8px;
    }
</style>
@endsection