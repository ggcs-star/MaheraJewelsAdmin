@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Edit Application Settings</h2>
            <small class="text-muted">Update application configuration</small>
        </div>
        <a href="{{ admin_route('app-settings.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ admin_route('app-settings.update',$appSetting) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Application Name <span class="text-danger">*</span></label>
                        <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ old('app_name',$appSetting->app_name) }}" required>
                        @error('app_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ $appSetting->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$appSetting->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Application Logo</label>
                        <input type="file" name="app_logo" class="form-control">
                        <small class="text-muted">Leave empty to keep current logo</small>
                        @if($appSetting->app_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->app_logo_url }}" class="rounded border p-1" height="70" style="height:70px; width:auto; max-width:150px; object-fit:contain;" alt="app logo">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Splash Screen Logo</label>
                        <input type="file" name="splash_logo" class="form-control">
                        <small class="text-muted">Leave empty to keep current logo</small>
                        @if($appSetting->splash_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->splash_logo_url }}" class="rounded border p-1" height="70" style="height:70px; width:auto; max-width:150px; object-fit:contain;" alt="splash logo">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Header Logo</label>
                        <input type="file" name="header_logo" class="form-control">
                        <small class="text-muted">Leave empty to keep current logo</small>
                        @if($appSetting->header_logo_url)
                            <div class="mt-2">
                                <img src="{{ $appSetting->header_logo_url }}" class="rounded border p-1" height="70" style="height:70px; width:auto; max-width:150px; object-fit:contain;" alt="header logo">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-4 text-end">
                <button type="submit" class="btn btn-success px-4 py-2">Update Settings</button>
            </div>
        </div>

    </form>

</div>

@endsection