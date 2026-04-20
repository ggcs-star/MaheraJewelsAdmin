@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Banners</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage promotional banners & placements</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Banner
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-base font-bold text-gray-800">Banner List</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-center">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Desktop Image</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Mobile Image</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Page</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Layout</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $banner->id }}</td>
                        <td class="px-4 py-3">
                            @if($banner->image)
                                <img src="{{ $banner->image }}"
                                     data-image="{{ $banner->image }}"
                                     class="w-20 h-12 rounded-lg border border-gray-200 object-cover cursor-zoom-in banner-preview shadow-sm">
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($banner->mobile_image)
                                <img src="{{ $banner->mobile_image }}"
                                     data-image="{{ $banner->mobile_image }}"
                                     class="w-12 h-16 rounded-lg border border-gray-200 object-cover cursor-zoom-in banner-preview shadow-sm">
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.banners.show', $banner) }}" class="text-sm font-bold text-gray-800 hover:text-[#8B2452] transition-colors">
                                {{ $banner->title ?? '—' }}
                            </a>
                            @if($banner->subtitle)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $banner->subtitle }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.banners.show', $banner) }}">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                    {{ ucfirst($banner->page) }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.banners.show', $banner) }}">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ ucfirst($banner->position) }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.banners.show', $banner) }}">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ ucfirst($banner->layout) }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.banners.show', $banner) }}">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $banner->status ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $banner->status ? 'Active' : 'Inactive' }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No banners found</p>
                            <p class="text-gray-400 text-xs mt-1">Add a new banner to get started</p>
                            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: var(--primary-light); color: white;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Banner
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade fixed inset-0 bg-black/70 z-50 hidden items-center justify-center" id="imagePreviewModal" tabindex="-1">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="relative">
            <button type="button" class="absolute top-2 right-2 p-2 text-gray-500 hover:text-gray-700 transition-colors" data-bs-dismiss="modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="p-4 flex justify-center items-center" style="max-height: 85vh;">
                <img id="previewImage" class="max-w-full max-h-[80vh] object-contain rounded-lg">
            </div>
        </div>
    </div>
</div>

<style>
.modal.fade {
    display: none;
}
.modal.fade.show {
    display: flex !important;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('imagePreviewModal');
    const previewImage = document.getElementById('previewImage');
    
    document.querySelectorAll('.banner-preview').forEach(img => {
        img.addEventListener('click', function (e) {
            e.stopPropagation();
            previewImage.src = this.dataset.image;
            modal.classList.add('show');
        });
    });
    
    modal.querySelector('[data-bs-dismiss="modal"]').addEventListener('click', function() {
        modal.classList.remove('show');
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
</script>
@endpush