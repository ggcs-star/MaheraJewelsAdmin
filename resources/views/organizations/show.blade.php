@extends('layouts.admin')

@section('content')
<div class="container-fluid organization-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ admin_route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ admin_route('organizations.index') }}">Organizations</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $organization->name }}
                    </li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-1">Organization Details</h2>
            <div class="text-muted">Organization ID: {{ $organization->id }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('organizations.edit', $organization) }}"
               class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i>Edit Organization
            </a>
            <a href="{{ admin_route('organizations.index') }}"
               class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    {{-- TOP STATS --}}
    <div class="row g-3 mb-4">

        {{-- STATUS --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-{{ $organization->is_active ? 'success' : 'secondary' }} bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-{{ $organization->is_active ? 'check-circle' : 'pause-circle' }}
                               text-{{ $organization->is_active ? 'success' : 'secondary' }} fa-lg"></i>
                        </div>
                        <div class="text-muted small">Status</div>
                    </div>
                    <div class="fw-bold fs-4">
                        {{ $organization->is_active ? 'Active' : 'Inactive' }}
                    </div>
                    <div class="small text-muted">Organization status</div>
                </div>
            </div>
        </div>

        {{-- EMAIL --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-envelope text-primary fa-lg"></i>
                        </div>
                        <div class="text-muted small">Email</div>
                    </div>
                    <div class="fw-bold fs-6">
                        {{ $organization->email ?? '—' }}
                    </div>
                    <div class="small text-muted">Official email</div>
                </div>
            </div>
        </div>

        {{-- MOBILE --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-phone text-info fa-lg"></i>
                        </div>
                        <div class="text-muted small">Mobile</div>
                    </div>
                    <div class="fw-bold fs-6">
                        {{ $organization->mobile ?? '—' }}
                    </div>
                    <div class="small text-muted">Contact number</div>
                </div>
            </div>
        </div>

        {{-- WEBSITE --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-globe text-warning fa-lg"></i>
                        </div>
                        <div class="text-muted small">Website</div>
                    </div>
                    <div class="fw-bold fs-6">
                        {{ $organization->website ?? '—' }}
                    </div>
                    <div class="small text-muted">Official website</div>
                </div>
            </div>
        </div>

    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- OVERVIEW --}}
        <div class="col-xl-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-building text-primary me-2"></i>
                        Organization Overview
                    </h5>
                </div>

                <div class="card-body text-center pt-0">

                    @if($organization->logo_url)
                        <div class="mb-3 border rounded overflow-hidden" style="height:140px;">
                            <img src="{{ $organization->logo_url }}"
                                 class="img-fluid h-100 w-100 object-fit-cover">
                        </div>
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width:100px;height:100px;font-size:36px;font-weight:600;">
                            {{ strtoupper(substr($organization->name,0,1)) }}
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">{{ $organization->name }}</h4>

                    <div class="mb-3 text-muted">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $organization->address ?? 'No address added' }}
                    </div>

                    <span class="badge rounded-pill px-4 py-2 fs-6
                        {{ $organization->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $organization->is_active ? 'Active' : 'Inactive' }}
                    </span>

                </div>
            </div>
        </div>

        {{-- DETAILS --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Basic Information
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <div class="text-muted small">Email</div>
                                <div class="fw-semibold">{{ $organization->email ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <div class="text-muted small">Mobile</div>
                                <div class="fw-semibold">{{ $organization->mobile ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <div class="text-muted small">Website</div>
                                <div class="fw-semibold">{{ $organization->website ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <div class="text-muted small">Address</div>
                                <div class="fw-semibold">{{ $organization->address ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
