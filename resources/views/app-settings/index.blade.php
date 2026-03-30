@extends('layouts.admin.admin-settings')

@section('settings-content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Application Settings</h4>
            <p class="text-muted mb-0">Manage application configuration</p>
        </div>

        @if($settings->isEmpty())
            <a href="{{ admin_route('app-settings.create') }}" class="btn btn-primary">Add Settings</a>
        @else
            <a href="{{ admin_route('app-settings.edit', $settings->first()) }}" class="btn btn-primary">Edit Settings</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="ps-3">ID</th>
                            <th>Name</th>
                            <th>App Logo</th>
                            <th>Splash Logo</th>
                            <th>Header Logo</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting)
                            <tr>
                                <td class="ps-3 fw-medium">{{ $setting->id }}</td>
                                <td class="fw-medium">{{ $setting->app_name }}</td>
                                <td>
                                    @if($setting->app_logo_url)
                                        <img src="{{ $setting->app_logo_url }}" class="logo-thumb" onclick="showImagePreview('{{ $setting->app_logo_url }}')" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" alt="app logo">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($setting->splash_logo_url)
                                        <img src="{{ $setting->splash_logo_url }}" class="logo-thumb" onclick="showImagePreview('{{ $setting->splash_logo_url }}')" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" alt="splash logo">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($setting->header_logo_url)
                                        <img src="{{ $setting->header_logo_url }}" class="logo-thumb" onclick="showImagePreview('{{ $setting->header_logo_url }}')" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" alt="header logo">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $setting->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill">
                                        {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ admin_route('app-settings.show', $setting) }}" class="btn btn-sm btn-info text-white">View</a>
                                    <a href="{{ admin_route('app-settings.edit', $setting) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ admin_route('app-settings.destroy', $setting) }}')">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No settings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" style="max-width:100%; max-height:400px; object-fit:contain;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this setting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.logo-thumb {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 4px;
    background: #fff;
    cursor: pointer;
    transition: transform 0.2s;
}
.logo-thumb:hover {
    transform: scale(1.1);
}
</style>

<script>
function showImagePreview(url) {
    document.getElementById('previewImage').src = url;
}

function confirmDelete(deleteUrl) {
    document.getElementById('deleteForm').action = deleteUrl;
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

@endsection