
@extends('layouts.admin')

@section('content')
<div class="category-page">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Categories</h1>
            <p class="text-muted small mb-0 mt-1">Manage your catalog hierarchy and visibility settings.</p>
        </div>
        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary px-4 shadow-sm fw-medium">
            <i class="fas fa-plus me-2"></i> + Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
            <h6 class="m-0 fw-bold text-primary text-uppercase small ls-1">Filters</h6>
            <button type="button" id="openFilterSidebar" class="btn btn-outline-secondary btn-sm ms-auto px-3">
                <i class="fas fa-sliders-h me-2"></i>Advanced Filter
            </button>
        </div>
        <div class="card-body bg-light-50">
            <form method="GET" id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.7rem;">Search Keywords</label>
                    <div class="input-group input-group-merge shadow-xs">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search opacity-50"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            id="categorySearch"
                            class="form-control border-start-0 ps-1 py-2 shadow-none"
                            placeholder="Name or slug"
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        <span
                            id="clearSearch"
                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                            style="cursor:pointer; display:none; z-index: 5;"
                        >
                            <i class="fas fa-times-circle"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.7rem;">Status</label>
                    <select name="status" class="form-control form-select auto-submit shadow-xs py-2 border">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.7rem;">Visibility</label>
                    <select name="visibility" class="form-control form-select auto-submit shadow-xs py-2 border">
                        <option value="">All Visibility</option>
                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.7rem;">Parent Category</label>
                    <select name="parent_id" class="form-control form-select auto-submit shadow-xs py-2 border">
                        <option value="">All Parents</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <h6 class="m-0 fw-bold text-primary text-uppercase small ls-1">Categories List</h6>
            <div class="d-flex gap-2 align-items-center">
                <form method="POST" action="{{ admin_route('categories.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 fw-medium border-0">
                        <i class="fas fa-trash-alt me-2"></i>Delete Selected
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr class="text-uppercase small fw-bold text-muted ls-1">
                            <th class="py-3 px-4 border-bottom" width="50">
                                <div class="form-check">
                                    <input class="form-check-input border-secondary" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="py-3 px-4 border-bottom">ID</th>
                            <th class="py-3 px-4 border-bottom">Name</th>
                            <th class="py-3 px-4 border-bottom">Slug</th>
                            <!-- <th class="py-3 px-4 border-bottom">Parent</th>
                            <th class="py-3 px-4 border-bottom text-center">Children</th> -->
                            <th class="py-3 px-4 border-bottom">Visibility</th>
                            <th class="py-3 px-4 border-bottom">Status</th>
                            <th class="py-3 px-4 border-bottom text-center" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="category-row align-middle border-bottom" data-id="{{ $category->id }}">
                                <td class="px-4">
                                    <div class="form-check">
                                        <input class="form-check-input row-checkbox border-secondary" type="checkbox" name="ids[]" value="{{ $category->id }}" form="bulkDeleteForm">
                                    </div>
                                </td>
                                <td class="px-4">
                                    <span class="badge bg-light text-muted font-monospace border px-2">{{ $category->serial }}</span>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($category->children->count())
                                            <button type="button" class="toggle-icon btn btn-sm btn-light border p-0 rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:24px;height:24px; font-size: 0.6rem;">
                                                ▶
                                            </button>
                                        @endif
                                        @if($category->image_url)
                                            <div class="position-relative">
                                                 <img 
        src="{{ Storage::disk('s3')->url($category->image_url) }}"
        data-full="{{ Storage::disk('s3')->url($category->image_url) }}"
        width="40"
        height="40"
        class="rounded border shadow-sm category-image object-fit-cover"
        onclick="event.stopPropagation()"
        style="cursor: zoom-in;"
    >
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.categories.details', $category->id) }}"
                                               class="category-name-link text-decoration-none text-dark fw-bold mb-0 hover-primary"
                                               onclick="event.stopPropagation()">
                                                {{ $category->name }}
                                            </a>
                                            @if($category->description)
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                                    {{ Str::limit($category->description, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 text-muted font-monospace small">
                                    <span class="bg-light px-2 rounded">{{ $category->slug }}</span>
                                </td>
                                <!-- <td class="px-4 text-muted opacity-50">—</td>
                                <td class="px-4 text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 border border-info border-opacity-25">{{ $category->children->count() }}</span>
                                </td> -->
                                <td class="px-4">
                                    <span class="badge rounded-pill fw-medium px-3 py-2 {{ $category->visibility == 'public' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                        {{ ucfirst($category->visibility) }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <span class="badge rounded-pill fw-medium px-3 py-2 {{ $category->status == 'active' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                        <i class="fas fa-circle me-1" style="font-size: 0.45rem; vertical-align: middle;"></i>
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ admin_route('categories.edit', $category) }}" 
                                           class="btn btn-sm btn-outline-primary px-3 fw-medium">
                                            Edit
                                        </a>
                                        <form action="{{ admin_route('categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger px-3 fw-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @foreach($category->children as $child)
                                <tr class="subcategory-row align-middle d-none parent-{{ $category->id }} bg-light bg-opacity-25">
                                    <td class="px-4">
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox border-secondary" type="checkbox" name="ids[]" value="{{ $child->id }}" form="bulkDeleteForm">
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-white text-muted font-monospace border px-2">#{{ $child->id }}</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center gap-2 ps-5 py-1">
                                            @if($child->image_url)
                                                <img src="{{ Storage::disk('s3')->url($child->image_url) }}"

                                                    width="32"
                                                    height="32"
                                                    class="rounded border shadow-xs category-image object-fit-cover"
                                                    data-full="{{ Storage::disk('s3')->url($child->image_url) }}"
                                                    onclick="event.stopPropagation()"
                                                >
                                            @endif
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('admin.categories.details', $child->id) }}"
                                                   onclick="event.stopPropagation()"
                                                   class="category-name-link text-decoration-none text-muted fw-semibold">
                                                    {{ $child->name }}
                                                </a>
                                                @if($child->description)
                                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 180px;">
                                                        {{ Str::limit($child->description, 40) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 text-muted font-monospace small">{{ $child->slug }}</td>
                                    <!-- <td class="px-4">
                                        <small class="text-primary fw-medium border px-2 py-1 rounded-pill bg-white">{{ $category->name }}</small>
                                    </td>
                                    <td class="px-4 text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-2">0</span>
                                    </td> -->
                                    <td class="px-4">
                                        <span class="badge rounded-pill fw-normal px-2 py-1 {{ $child->visibility == 'public' ? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                            {{ ucfirst($child->visibility) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge rounded-pill fw-normal px-2 py-1 {{ $child->status == 'active' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-10' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                            {{ ucfirst($child->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ admin_route('categories.edit', $child) }}" 
                                               class="btn btn-sm btn-outline-primary px-3 fw-medium">
                                                Edit
                                            </a>
                                            <form action="{{ admin_route('categories.destroy', $child) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger px-3 fw-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-5">
                                        <div class="mb-3 text-muted opacity-25">
                                            <i class="fas fa-folder-open fa-4x"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No categories found</h5>
                                        <p class="text-muted small">Try adjusting your filters or add a new category to get started.</p>
                                        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary btn-sm px-4 py-2 mt-2">
                                            <i class="fas fa-plus me-2"></i>Create New Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small fw-medium">
                    Showing <span class="text-dark fw-bold">{{ $categories->firstItem() ?? 0 }}</span> to <span class="text-dark fw-bold">{{ $categories->lastItem() ?? 0 }}</span> of <span class="text-dark fw-bold">{{ $categories->total() }}</span> entries
                </div>
                <div>
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal shadow-lg">
    <div class="modal-content border-0 rounded-4 overflow-hidden">
        <img id="previewImage" class="img-fluid" />
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar shadow-2xl">
    <div class="filter-sidebar-header bg-white text-dark py-4 px-4 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-filter me-2 text-primary"></i>Advanced Filter
        </h5>
        <button type="button" id="closeFilterSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="filter-sidebar-body p-4 bg-light bg-opacity-50">
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Select Field</label>
            <select id="advField" class="form-control form-select shadow-xs py-2 border">
                <option value="name">Name</option>
                <option value="slug">Slug</option>
                <option value="status">Status</option>
                <option value="visibility">Visibility</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Condition</label>
            <select id="advCondition" class="form-control form-select shadow-xs py-2 border">
              <option value="like">Contains</option>
    <option value="=">Equals</option>
    <option value="!=">Not Equals</option>
    <option value="starts_with">Starts With</option>
    <option value="ends_with">Ends With</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Filter Value</label>
            <input type="text" id="advValue" class="form-control shadow-xs py-2 border" placeholder="Type here...">
        </div>
        <button type="button" id="applyAdvancedFilter" class="btn btn-primary w-100 py-3 rounded-3 shadow fw-bold">
            <i class="fas fa-check-circle me-2"></i>Apply Changes
        </button>
        <button
    type="button"
    id="clearAdvancedFilter"
    class="btn btn-outline-secondary w-100 mt-3 py-2 rounded-3 fw-medium">
    <i class="fas fa-times-circle me-2"></i>Clear Filter
</button>

    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.05rem; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .bg-light-50 { background-color: rgba(248, 249, 250, 0.5); }
    .hover-primary:hover { color: var(--bs-primary) !important; }
</style>
</div>
@endsection
