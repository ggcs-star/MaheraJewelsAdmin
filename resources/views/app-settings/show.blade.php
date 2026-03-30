@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Application Settings</h2>
            <small class="text-muted">View application configuration</small>
        </div>
        <a href="{{ admin_route('app-settings.edit',$appSetting) }}" class="btn btn-primary">Edit Settings</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Application Name</label>
                    <div class="form-control">{{ $appSetting->app_name }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-control">{{ $appSetting->is_active ? 'Active' : 'Inactive' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Application Logo</label>
                    @if($appSetting->app_logo_url)
                        <div class="mt-2">
                            <img src="{{ $appSetting->app_logo_url }}" class="rounded border" style="height:80px; width:auto; max-width:150px; object-fit:contain;">
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Splash Logo</label>
                    @if($appSetting->splash_logo_url)
                        <div class="mt-2">
                            <img src="{{ $appSetting->splash_logo_url }}" class="rounded border" style="height:80px; width:auto; max-width:150px; object-fit:contain;">
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Header Logo</label>
                    @if($appSetting->header_logo_url)
                        <div class="mt-2">
                            <img src="{{ $appSetting->header_logo_url }}" class="rounded border" style="height:80px; width:auto; max-width:150px; object-fit:contain;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection