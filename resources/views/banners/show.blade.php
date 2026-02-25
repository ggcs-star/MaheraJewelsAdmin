@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('banners.index') }}">Banners</a></li>
                    <li class="breadcrumb-item active">{{ $banner->title ?? 'Banner Details' }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Banner Details</h2>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('banners.edit', $banner) }}" class="btn btn-primary px-4">
                Edit Banner
            </a>
            <a href="{{ admin_route('banners.index') }}" class="btn btn-outline-secondary px-4">
                Back
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Status</div>
                    <div class="fw-bold fs-4">
                        <span class="badge {{ $banner->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $banner->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Page</div>
                    <div class="fw-bold fs-4 text-capitalize">
                        {{ $banner->page }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Position</div>
                    <div class="fw-bold fs-4 text-capitalize">
                        {{ $banner->position }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Layout</div>
                    <div class="fw-bold fs-4 text-capitalize">
                        {{ $banner->layout }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    Banner Images
                </div>
                <div class="card-body text-center">

                    @if($banner->image)
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Desktop Image</div>
                            <img src="{{ $banner->image }}"
                                 data-full="{{ $banner->image }}"
                                 class="img-fluid rounded border banner-thumb"
                                 style="cursor:zoom-in;">
                        </div>
                    @endif

                    @if($banner->mobile_image)
                        <div>
                            <div class="text-muted small mb-1">Mobile Image</div>
                            <img src="{{ $banner->mobile_image }}"
                                 data-full="{{ $banner->mobile_image }}"
                                 class="img-fluid rounded border banner-thumb"
                                 style="max-width:220px;cursor:zoom-in;">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    Basic Information
                </div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <div class="text-muted small">Title</div>
                        <div class="fw-semibold">
                            {{ $banner->title ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Subtitle</div>
                        <div class="fw-semibold">
                            {{ $banner->subtitle ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Button Text</div>
                        <div class="fw-semibold">
                            {{ $banner->button_text ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Button Link</div>
                        <div class="fw-semibold text-break">
                            {{ $banner->button_link ?? '—' }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    Display Settings
                </div>
                <div class="card-body row g-3">

                    <div class="col-md-4">
                        <div class="text-muted small">Text Color</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded border"
                                  style="width:22px;height:22px;background:{{ $banner->text_color }}"></span>
                            <span class="fw-semibold">{{ $banner->text_color }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">Sort Order</div>
                        <div class="fw-semibold">
                            {{ $banner->sort_order ?? 0 }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal">
    <div class="modal-content">
        <img id="previewImage" class="img-fluid">
    </div>
</div>

<style>
.image-preview-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.75);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}
.image-preview-modal.active{display:flex}
.image-preview-modal .modal-content{max-width:90%;max-height:90%}
</style>

<script>
document.querySelectorAll('.banner-thumb').forEach(img=>{
    img.onclick=e=>{
        e.stopPropagation();
        previewImage.src=img.dataset.full;
        imagePreviewModal.classList.add('active');
    }
});
imagePreviewModal.onclick=()=>imagePreviewModal.classList.remove('active');
</script>
@endsection