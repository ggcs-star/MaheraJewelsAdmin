@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 fw-bold text-gray-800">Edit Banner</h1>
            <p class="text-muted small mb-0 mt-1">
                Update banner details & images.
            </p>
        </div>
        <a href="{{ admin_route('banners.index') }}" class="btn btn-secondary px-4">
            Back
        </a>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('banners.update', $banner) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BASIC --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><strong>Basic Information</strong></div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Heading</label>
                    <input type="text" name="title"
                           value="{{ old('title',$banner->title) }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Sub Heading</label>
                    <input type="text" name="subtitle"
                           value="{{ old('subtitle',$banner->subtitle) }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Desktop Image</label>
                    <input type="file" name="image" class="form-control">
                    @if($banner->image)
                        <img src="{{ $banner->image }}"
                             class="mt-2 rounded border"
                             width="220">
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Image</label>
                    <input type="file" name="mobile_image" class="form-control">
                    @if($banner->mobile_image)
                        <img src="{{ $banner->mobile_image }}"
                             class="mt-2 rounded border"
                             width="120">
                    @endif
                </div>

            </div>
        </div>

        {{-- PLACEMENT --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><strong>Placement</strong></div>
            <div class="card-body row">

                <div class="col-md-4">
                    <label class="form-label">Page</label>
                    <select name="page" class="form-control">
                        <option value="home" {{ $banner->page=='home'?'selected':'' }}>Home</option>
                        <option value="category" {{ $banner->page=='category'?'selected':'' }}>Category</option>
                        <option value="product" {{ $banner->page=='product'?'selected':'' }}>Product</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Position</label>
                    <select name="position" class="form-control">
                        <option value="hero" {{ $banner->position=='hero'?'selected':'' }}>Hero</option>
                        <option value="mid" {{ $banner->position=='mid'?'selected':'' }}>Middle</option>
                        <option value="bottom" {{ $banner->position=='bottom'?'selected':'' }}>Bottom</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Layout</label>
                    <select name="layout" class="form-control">
                        <option value="right" {{ $banner->layout=='right'?'selected':'' }}>Right</option>
                        <option value="left" {{ $banner->layout=='left'?'selected':'' }}>Left</option>
                        <option value="center" {{ $banner->layout=='center'?'selected':'' }}>Center</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- CTA + DISPLAY --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><strong>CTA & Display</strong></div>
            <div class="card-body row">

                <div class="col-md-4">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="button_text"
                           value="{{ old('button_text',$banner->button_text) }}"
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Button Link</label>
                    <input type="text" name="button_link"
                           value="{{ old('button_link',$banner->button_link) }}"
                           class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Text Color</label>
                    <input type="color" name="text_color"
                           value="{{ old('text_color',$banner->text_color) }}"
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

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $banner->status ? 'selected':'' }}>Active</option>
                        <option value="0" {{ !$banner->status ? 'selected':'' }}>Inactive</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- ACTION --}}
        <div class="text-end">
            <button class="btn btn-primary px-5">
                Update Banner
            </button>
        </div>

    </form>
</div>
@endsection