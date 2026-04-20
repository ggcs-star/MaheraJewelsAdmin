@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-5">

    <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}" style="color: #440C2C;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('banners.index') }}" style="color: #440C2C;">Banners</a></li>
                    <li class="breadcrumb-item active" style="color: #64748b;">{{ $banner->title ?? 'Banner Details' }}</li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-gray-800">Banner Details</h2>
        </div>

        <div class="flex gap-2">
            <a href="{{ admin_route('banners.edit', $banner) }}" class="px-4 py-2 rounded-lg transition" style="background: #440C2C; color: white;">
                Edit Banner
            </a>
            <a href="{{ admin_route('banners.index') }}" class="px-4 py-2 rounded-lg transition" style="background: #f1f5f9; color: #64748b;">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-muted text-xs mb-1" style="color: #64748b;">Status</div>
            <div class="font-bold text-xl mt-1">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $banner->status ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $banner->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-muted text-xs mb-1" style="color: #64748b;">Page</div>
            <div class="font-bold text-xl mt-1 text-capitalize" style="color: #1e293b;">
                {{ $banner->page }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-muted text-xs mb-1" style="color: #64748b;">Position</div>
            <div class="font-bold text-xl mt-1 text-capitalize" style="color: #1e293b;">
                {{ $banner->position }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-muted text-xs mb-1" style="color: #64748b;">Layout</div>
            <div class="font-bold text-xl mt-1 text-capitalize" style="color: #1e293b;">
                {{ $banner->layout }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <h5 class="font-semibold text-gray-800">Banner Images</h5>
                </div>
                <div class="p-5 text-center">

                    @if($banner->image)
                        <div class="mb-4">
                            <div class="text-muted text-xs mb-2" style="color: #64748b;">Desktop Image</div>
                            <img src="{{ $banner->image }}"
                                 data-full="{{ $banner->image }}"
                                 class="img-fluid rounded-lg border border-gray-200 banner-thumb"
                                 style="cursor:zoom-in; max-height: 180px;">
                        </div>
                    @endif

                    @if($banner->mobile_image)
                        <div>
                            <div class="text-muted text-xs mb-2" style="color: #64748b;">Mobile Image</div>
                            <img src="{{ $banner->mobile_image }}"
                                 data-full="{{ $banner->mobile_image }}"
                                 class="img-fluid rounded-lg border border-gray-200 banner-thumb"
                                 style="max-width:180px; cursor:zoom-in;">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <h5 class="font-semibold text-gray-800">Basic Information</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Title</div>
                            <div class="font-semibold" style="color: #1e293b;">
                                {{ $banner->title ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Subtitle</div>
                            <div class="font-semibold" style="color: #1e293b;">
                                {{ $banner->subtitle ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Button Text</div>
                            <div class="font-semibold" style="color: #1e293b;">
                                {{ $banner->button_text ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Button Link</div>
                            <div class="font-semibold text-break" style="color: #1e293b;">
                                {{ $banner->button_link ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <h5 class="font-semibold text-gray-800">Display Settings</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Text Color</div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-lg border border-gray-200" style="width:24px; height:24px; background: {{ $banner->text_color ?? '#000000' }}"></span>
                                <span class="font-semibold" style="color: #1e293b;">{{ $banner->text_color ?? '#000000' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted text-xs mb-1" style="color: #64748b;">Sort Order</div>
                            <div class="font-semibold" style="color: #1e293b;">
                                {{ $banner->sort_order ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="imagePreviewModal" class="fixed inset-0 bg-black/75 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-[90%] max-h-[90%] overflow-hidden p-2">
        <img id="previewImage" class="img-fluid rounded-lg" style="max-height: 80vh; max-width: 100%;">
    </div>
</div>

<script>
const imagePreviewModal = document.getElementById('imagePreviewModal');
const previewImage = document.getElementById('previewImage');

document.querySelectorAll('.banner-thumb').forEach(img => {
    img.onclick = (e) => {
        e.stopPropagation();
        previewImage.src = img.dataset.full;
        imagePreviewModal.classList.remove('hidden');
        imagePreviewModal.classList.add('flex');
    }
});

imagePreviewModal.onclick = () => {
    imagePreviewModal.classList.add('hidden');
    imagePreviewModal.classList.remove('flex');
}
</script>
@endsection