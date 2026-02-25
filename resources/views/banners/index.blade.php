@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 fw-bold text-gray-800">Banners</h1>
            <p class="text-muted small mb-0 mt-1">
                Manage promotional banners & placements.
            </p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary px-4">
            + Add Banner
        </a>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <strong>Banner List</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th>#</th>
                        <th>Desktop Image</th>
                        <th>Mobile Image</th>
                        <th>Title</th>
                        <th>Page</th>
                        <th>Position</th>
                        <th>Layout</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>{{ $banner->id }}</td>

                        {{-- DESKTOP IMAGE --}}
                        <td>
                            @if($banner->image)
                                <img
                                    src="{{ $banner->image }}"
                                    data-image="{{ $banner->image }}"
                                    class="rounded border shadow-sm banner-preview"
                                    width="90"
                                    height="45"
                                    style="object-fit:cover; cursor:pointer;">
                            @else
                                —
                            @endif
                        </td>

                        {{-- MOBILE IMAGE --}}
                        <td>
                            @if($banner->mobile_image)
                                <img
                                    src="{{ $banner->mobile_image }}"
                                    data-image="{{ $banner->mobile_image }}"
                                    class="rounded border shadow-sm banner-preview"
                                    width="45"
                                    height="70"
                                    style="object-fit:cover; cursor:pointer;">
                            @else
                                —
                            @endif
                        </td>

                       <td>
    <a href="{{ route('admin.banners.show', $banner) }}"
       class="banner-link fw-semibold">
        {{ $banner->title ?? '—' }}
    </a>
    <br>
    <small class="text-muted">{{ $banner->subtitle }}</small>
</td>
                        <td>
    <a href="{{ route('admin.banners.show', $banner) }}"
       class="banner-link">
        <span class="badge bg-info bg-opacity-10 text-info">
            {{ ucfirst($banner->page) }}
        </span>
    </a>
</td>
                        <td>
    <a href="{{ route('admin.banners.show', $banner) }}"
       class="banner-link">
        <span class="badge bg-secondary bg-opacity-10 text-secondary">
            {{ ucfirst($banner->position) }}
        </span>
    </a>
</td>

                       <td>
    <a href="{{ route('admin.banners.show', $banner) }}"
       class="banner-link">
        <span class="badge bg-dark bg-opacity-10 text-dark">
            {{ ucfirst($banner->layout) }}
        </span>
    </a>
</td>

                       <td>
    <a href="{{ route('admin.banners.show', $banner) }}"
       class="banner-link">
        <span class="badge {{ $banner->status
            ? 'bg-success bg-opacity-10 text-success'
            : 'bg-danger bg-opacity-10 text-danger' }}">
            {{ $banner->status ? 'Active' : 'Inactive' }}
        </span>
    </a>
</td>

                        {{-- ACTIONS --}}
                        <td class="text-center">
                            <a href="{{ route('admin.banners.edit', $banner) }}"
                               class="btn btn-sm btn-outline-primary px-3">
                                Edit
                            </a>

                            <form action="{{ route('admin.banners.destroy', $banner) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this banner?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger px-3">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            No banners found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $banners->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 rounded-4 bg-transparent shadow-none">

            <div class="position-relative bg-white rounded-4 shadow-lg p-3">
                <button type="button"
                        class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>

                <div class="d-flex justify-content-center align-items-center"
                     style="max-height:75vh;">
                    <img id="previewImage"
                         class="img-fluid rounded-3"
                         style="
                            max-height:70vh;
                            max-width:100%;
                            object-fit:contain;
                         ">
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.banner-preview').forEach(img => {
        img.addEventListener('click', function (e) {
            e.stopPropagation();
            document.getElementById('previewImage').src = this.dataset.image;
            new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
        });
    });
});
</script>
@endpush