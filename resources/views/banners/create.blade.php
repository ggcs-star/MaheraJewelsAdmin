@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 fw-bold text-gray-800">Create Banner</h1>
            <p class="text-muted small mb-0 mt-1">Create promotional banners (AJIO style layout)</p>
        </div>
        <a href="{{ admin_route('banners.index') }}" class="btn btn-outline-secondary px-4">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Basic Information</div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Heading</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="MIN 60% OFF">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Sub Heading</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                           class="form-control @error('subtitle') is-invalid @enderror"
                           placeholder="Fine Silver Jewellery">
                    @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Desktop Image <span class="text-danger">*</span></label>
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">JPG / PNG / WEBP · Max 2MB</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Image</label>
                    <input type="file" name="mobile_image"
                           class="form-control @error('mobile_image') is-invalid @enderror">
                    @error('mobile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Optional · Recommended 800×900</small>
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Placement</div>
            <div class="card-body row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Page <span class="text-danger">*</span></label>
                    <select name="page" class="form-control @error('page') is-invalid @enderror" required>
                        <option value="">Select</option>
                        <option value="home" {{ old('page')=='home'?'selected':'' }}>Home</option>
                        <option value="category" {{ old('page')=='category'?'selected':'' }}>Category</option>
                        <option value="product" {{ old('page')=='product'?'selected':'' }}>Product</option>
                    </select>
                    @error('page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Position <span class="text-danger">*</span></label>
                    <select name="position" class="form-control @error('position') is-invalid @enderror" required>
                        <option value="">Select</option>
                        <option value="hero" {{ old('position')=='hero'?'selected':'' }}>Hero</option>
                        <option value="mid" {{ old('position')=='mid'?'selected':'' }}>Middle</option>
                        <option value="bottom" {{ old('position')=='bottom'?'selected':'' }}>Bottom</option>
                    </select>
                    @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Layout <span class="text-danger">*</span></label>
                    <select name="layout" class="form-control @error('layout') is-invalid @enderror" required>
                        <option value="">Select</option>
                        <option value="right" {{ old('layout')=='right'?'selected':'' }}>Right Text</option>
                        <option value="left" {{ old('layout')=='left'?'selected':'' }}>Left Text</option>
                        <option value="center" {{ old('layout')=='center'?'selected':'' }}>Center Text</option>
                    </select>
                    @error('layout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Call To Action</div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text') }}"
                           class="form-control @error('button_text') is-invalid @enderror"
                           placeholder="SHOP NOW">
                    @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Button Link</label>
                    <input type="text" name="button_link" value="{{ old('button_link') }}"
                           class="form-control @error('button_link') is-invalid @enderror"
                           placeholder="/category/sale">
                    @error('button_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Display Settings</div>
            <div class="card-body row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Text Color</label>
                    <input type="color" name="text_color"
                           value="{{ old('text_color','#ffffff') }}"
                           class="form-control form-control-color">
                </div>
                <div class="col-md-3 mb-3">
    <label class="form-label">Start Date</label>
    <input type="date"
           name="start_date"
           value="{{ old('start_date', $banner->start_date ?? '') }}"
           class="form-control">
</div>

<div class="col-md-3 mb-3">
    <label class="form-label">End Date</label>
    <input type="date"
           name="end_date"
           value="{{ old('end_date', $banner->end_date ?? '') }}"
           class="form-control">
</div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order"
                           value="{{ old('sort_order',0) }}"
                           class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="1" {{ old('status',1)==1?'selected':'' }}>Active</option>
                        <option value="0" {{ old('status')==='0'?'selected':'' }}>Inactive</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="text-end">
            <a href="{{ admin_route('banners.index') }}" class="btn btn-secondary px-4">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary px-5">
                Save Banner
            </button>
        </div>

    </form>
</div>
@endsection