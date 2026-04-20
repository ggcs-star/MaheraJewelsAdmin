@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-5">

    <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create Banner</h1>
            <p class="text-sm text-gray-500 mt-1">Create promotional banners (AJIO style layout)</p>
        </div>
        <a href="{{ admin_route('banners.index') }}" class="px-4 py-2 rounded-lg transition" style="background: #f1f5f9; color: #64748b;">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
            <ul class="mb-0 list-disc list-inside text-red-600 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Basic Information</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Heading</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="MIN 60% OFF"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('title')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Sub Heading</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                               class="form-control @error('subtitle') is-invalid @enderror"
                               placeholder="Fine Silver Jewellery"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('subtitle')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">
                            Desktop Image <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="file" name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('image')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                        <small class="text-muted" style="font-size: 11px;">JPG / PNG / WEBP · Max 2MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Mobile Image</label>
                        <input type="file" name="mobile_image"
                               class="form-control @error('mobile_image') is-invalid @enderror"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('mobile_image')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                        <small class="text-muted" style="font-size: 11px;">Optional · Recommended 800×900</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Placement</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">
                            Page <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="page" class="form-control @error('page') is-invalid @enderror" required style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="">Select</option>
                            <option value="home" {{ old('page')=='home'?'selected':'' }}>Home</option>
                            <option value="category" {{ old('page')=='category'?'selected':'' }}>Category</option>
                            <option value="product" {{ old('page')=='product'?'selected':'' }}>Product</option>
                        </select>
                        @error('page')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">
                            Position <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="position" class="form-control @error('position') is-invalid @enderror" required style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="">Select</option>
                            <option value="hero" {{ old('position')=='hero'?'selected':'' }}>Hero</option>
                            <option value="mid" {{ old('position')=='mid'?'selected':'' }}>Middle</option>
                            <option value="bottom" {{ old('position')=='bottom'?'selected':'' }}>Bottom</option>
                        </select>
                        @error('position')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">
                            Layout <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="layout" class="form-control @error('layout') is-invalid @enderror" required style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="">Select</option>
                            <option value="right" {{ old('layout')=='right'?'selected':'' }}>Right Text</option>
                            <option value="left" {{ old('layout')=='left'?'selected':'' }}>Left Text</option>
                            <option value="center" {{ old('layout')=='center'?'selected':'' }}>Center Text</option>
                        </select>
                        @error('layout')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Call To Action</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Button Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text') }}"
                               class="form-control @error('button_text') is-invalid @enderror"
                               placeholder="SHOP NOW"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('button_text')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Button Link</label>
                        <input type="text" name="button_link" value="{{ old('button_link') }}"
                               class="form-control @error('button_link') is-invalid @enderror"
                               placeholder="/category/sale"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @error('button_link')<div class="invalid-feedback" style="color: #dc2626; font-size: 12px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Display Settings</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Text Color</label>
                        <input type="color" name="text_color"
                               value="{{ old('text_color','#ffffff') }}"
                               class="form-control form-control-color"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px; height: 42px;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Start Date</label>
                        <input type="date"
                               name="start_date"
                               value="{{ old('start_date', $banner->start_date ?? '') }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">End Date</label>
                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date', $banner->end_date ?? '') }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Sort Order</label>
                        <input type="number" name="sort_order"
                               value="{{ old('sort_order',0) }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">
                            Status <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="status" class="form-control" required style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="1" {{ old('status',1)==1?'selected':'' }}>Active</option>
                            <option value="0" {{ old('status')==='0'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ admin_route('banners.index') }}" class="px-5 py-2 rounded-lg transition" style="background: #f1f5f9; color: #64748b;">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 rounded-lg transition" style="background: #440C2C; color: white;">
                Save Banner
            </button>
        </div>

    </form>
</div>
@endsection