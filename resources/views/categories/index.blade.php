@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Categories</h2>
        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="card mb-4" id="filterForm">
        <div class="card-body row g-2">

            <div class="col-md-3">
                <div class="position-relative">
                    <input
                        type="text"
                        name="search"
                        id="categorySearch"
                        class="form-control pe-5"
                        placeholder="Search name or slug"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >

                    <span
                        id="clearSearch"
                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                        style="cursor:pointer; display:none;"
                    >
                        ✕
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="visibility" class="form-control">
                    <option value="">All Visibility</option>
                    <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="parent_id" class="form-control">
                    <option value="">All Parents</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-secondary w-100">Filter</button>
            </div>

        </div>
    </form>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-white border-bottom">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Children</th>
                        <th>Visibility</th>
                        <th>Status</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody>
                  @forelse($categories as $category)
    <tr
        style="cursor:pointer"
        onclick="window.location='{{ route('admin.categories.details', $category->id) }}'"
    >

                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->parent)
                                    &mdash; {{ $category->name }}
                                @else
                                    <strong>{{ $category->name }}</strong>
                                @endif
                            </td>
                            <td class="text-muted">{{ $category->slug }}</td>
                            <td class="text-muted">{{ $category->parent?->name ?? '—' }}</td>
                            <td><span class="fw-semibold">{{ $category->children->count() }}</span></td>

                            <td><span class="badge rounded-pill bg-primary-subtle text-primary px-3">{{ ucfirst($category->visibility) }}</span></td>
                            <td>
                                <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <a href="{{ admin_route('categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ admin_route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->appends(request()->query())->links() }}
    </div>
</div>
<script>
    const searchInput = document.getElementById('categorySearch');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('filterForm');

    let debounceTimer;

    function toggleClearIcon() {
        clearBtn.style.display = searchInput.value ? 'block' : 'none';
    }

    searchInput.addEventListener('input', function () {
        toggleClearIcon();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 400);
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        toggleClearIcon();
        form.submit();
    });

    toggleClearIcon();
</script>

@endsection