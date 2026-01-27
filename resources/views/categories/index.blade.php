@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories</h1>
        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            <button type="button" id="openFilterSidebar" class="btn btn-outline-secondary btn-sm ms-auto">
                <i class="fas fa-sliders-h me-2"></i>Advanced Filter
            </button>
        </div>
        <div class="card-body">
            <form method="GET" id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Search</label>
                    <div class="input-group position-relative">
    <span class="input-group-text bg-transparent border-end-0">
        <i class="fas fa-search text-muted"></i>
    </span>

    <input
        type="text"
        name="search"
        id="categorySearch"
        class="form-control border-start-0 pe-5"
        placeholder="Name or slug"
        value="{{ request('search') }}"
        autocomplete="off"
    >

    <span
        id="clearSearch"
        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
        style="cursor:pointer; display:none;"
    >
        <i class="fas fa-times-circle"></i>
    </span>
</div>

                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-control form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Visibility</label>
                    <select name="visibility" class="form-control form-select">
                        <option value="">All Visibility</option>
                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Parent Category</label>
                    <select name="parent_id" class="form-control form-select">
                        <option value="">All Parents</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
            <div class="d-flex gap-2 align-items-center">
                <form method="POST" action="{{ route('admin.categories.bulk-delete') }}" id="bulkDeleteForm" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt me-2"></i>Delete Selected
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 border-bottom" width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="py-3 px-4 border-bottom">ID</th>
                            <th class="py-3 px-4 border-bottom">Name</th>
                            <th class="py-3 px-4 border-bottom">Slug</th>
                            <th class="py-3 px-4 border-bottom">Parent</th>
                            <th class="py-3 px-4 border-bottom">Children</th>
                            <th class="py-3 px-4 border-bottom">Visibility</th>
                            <th class="py-3 px-4 border-bottom">Status</th>
                            <th class="py-3 px-4 border-bottom text-center" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="category-row align-middle" data-id="{{ $category->id }}">
                                <td class="px-4">
                                    <div class="form-check">
                                        <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $category->id }}">
                                    </div>
                                </td>
                                <td class="px-4">
                                    <span class="badge bg-light text-dark font-monospace">#{{ $category->id }}</span>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($category->children->count())
                                            <button type="button" class="toggle-icon btn btn-sm btn-outline-secondary p-0 rounded-circle" style="width:24px;height:24px;">
                                                ▶
                                            </button>
                                        @endif
                                        @if($category->image_url)
                                            <div class="position-relative">
                                                <img
                                                    src="{{ asset('storage/'.$category->image_url) }}"
                                                    width="36"
                                                    height="36"
                                                    class="rounded border category-image"
                                                    data-full="{{ asset('storage/'.$category->image_url) }}"
                                                    onclick="event.stopPropagation()"
                                                >
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.categories.details', $category->id) }}"
                                               class="category-name-link text-decoration-none text-dark fw-semibold"
                                               onclick="event.stopPropagation()">
                                                {{ $category->name }}
                                            </a>
                                            @if($category->description)
                                                <small class="text-muted text-truncate" style="max-width: 200px;">
                                                    {{ Str::limit($category->description, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 text-muted font-monospace small">{{ $category->slug }}</td>
                                <td class="px-4 text-muted">—</td>
                                <td class="px-4">
                                    <span class="badge bg-info rounded-pill">{{ $category->children->count() }}</span>
                                </td>
                                <td class="px-4">
                                    <span class="badge rounded-pill {{ $category->visibility == 'public' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ ucfirst($category->visibility) }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <span class="badge rounded-pill {{ $category->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ admin_route('categories.edit', $category) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ admin_route('categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @foreach($category->children as $child)
                                <tr class="subcategory-row align-middle d-none parent-{{ $category->id }}">
                                    <td class="px-4">
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $child->id }}">
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark font-monospace">#{{ $child->id }}</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center gap-2 ps-4">
                                            @if($child->image_url)
                                                <img
                                                    src="{{ asset('storage/'.$child->image_url) }}"
                                                    width="30"
                                                    height="30"
                                                    class="rounded border category-image"
                                                    data-full="{{ asset('storage/'.$child->image_url) }}"
                                                    onclick="event.stopPropagation()"
                                                >
                                            @endif
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('admin.categories.details', $child->id) }}"
                                                   onclick="event.stopPropagation()"
                                                   class="category-name-link text-decoration-none text-muted">
                                                    {{ $child->name }}
                                                </a>
                                                @if($child->description)
                                                    <small class="text-muted text-truncate" style="max-width: 180px;">
                                                        {{ Str::limit($child->description, 40) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 text-muted font-monospace small">{{ $child->slug }}</td>
                                    <td class="px-4">
                                        <small class="text-primary">{{ $category->name }}</small>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-secondary rounded-pill">0</span>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge rounded-pill {{ $child->visibility == 'public' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ ucfirst($child->visibility) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge rounded-pill {{ $child->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            {{ ucfirst($child->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ admin_route('categories.edit', $child) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <form action="{{ admin_route('categories.destroy', $child) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
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
                                    <div class="py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No categories found</h5>
                                        <p class="text-muted small">Try adjusting your filters or add a new category</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 pt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
                </div>
                <div>
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal">
    <div class="modal-content">
        <img id="previewImage" class="img-fluid" />
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Advanced Filter
        </h5>
        <button type="button" id="closeFilterSidebar" class="btn btn-sm btn-light">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="filter-sidebar-body">
        <div class="mb-4">
            <label class="form-label fw-semibold">Field</label>
            <select id="advField" class="form-control form-select">
                <option value="name">Name</option>
                <option value="slug">Slug</option>
                <option value="status">Status</option>
                <option value="visibility">Visibility</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Condition</label>
            <select id="advCondition" class="form-control form-select">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Value</label>
            <input type="text" id="advValue" class="form-control" placeholder="Enter value">
        </div>
        <button type="button" id="applyAdvancedFilter" class="btn btn-primary w-100">
            <i class="fas fa-check me-2"></i>Apply Filter
        </button>
    </div>
</div>
@endsection