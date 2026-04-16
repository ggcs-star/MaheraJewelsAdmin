@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Edit Reel</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reels.update', $reel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Product</label>
                        <select name="platform_product_id" class="form-control" required>
                            @foreach($products as $id => $name)
                                <option value="{{ $id }}" {{ $reel->platform_product_id == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $reel->title }}">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ $reel->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Replace Video (optional)</label>
                        <input type="file" name="video" class="form-control">
                        <br>
                        <video width="200" controls>
                            <source src="{{ \App\Helpers\S3Helper::url($reel->video) }}" type="video/mp4">
                        </video>
                    </div>
                    <div class="mb-3">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ $reel->sort_order ?? 0 }}">
                        <small class="text-muted">Lower number = higher priority</small>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $reel->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$reel->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button class="btn btn-success">Update Reel</button>
                    <a href="{{ route('admin.reels.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection