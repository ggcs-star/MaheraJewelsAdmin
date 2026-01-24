@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Create Category</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('categories.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="card mb-4">
            <div class="card-header"><strong>Basic Information</strong></div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Category Name *</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Slug *</label>
                    <input type="text"
                           name="slug"
                           class="form-control"
                           value="{{ old('slug') }}"
                           placeholder="example-category"
                           required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="">— None (Main Category) —</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}"
                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Image </label>
                    <input type="file"
                           name="image_url"
                           class="form-control"
                           value="{{ old('image_url') }}">
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>SEO Settings</strong></div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text"
                           name="meta_title"
                           class="form-control"
                           value="{{ old('meta_title') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text"
                           name="meta_keywords"
                           class="form-control"
                           value="{{ old('meta_keywords') }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description"
                              class="form-control"
                              rows="2">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong>Settings</strong></div>
            <div class="card-body row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number"
                           name="sort_order"
                           class="form-control"
                           value="{{ old('sort_order', 0) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Featured</label>
                    <select name="is_featured" class="form-control">
                        <option value="0">No</option>
                        <option value="1" {{ old('is_featured') == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Visibility *</label>
                    <select name="visibility" class="form-control" required>
                        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ admin_route('categories.index') }}" class="btn btn-secondary">
                Back
            </a>
            <button type="submit" class="btn btn-primary">
                Save Category
            </button>
        </div>

    </form>
</div>
@endsection
