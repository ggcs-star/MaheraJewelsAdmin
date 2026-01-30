@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">{{ $warehouse->name }}</h3>
            <div class="text-muted small mt-1">
                <i class="fas fa-barcode me-1"></i>
                Code: {{ $warehouse->code }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('warehouses.edit', $warehouse) }}"
               class="btn btn-primary px-4">
                <i class="fas fa-edit me-1"></i> Edit
            </a>

            <a href="{{ admin_route('warehouses.index') }}"
               class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- MAIN DETAILS --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex align-items-center">
                    <i class="fas fa-warehouse text-primary me-2"></i>
                    Warehouse Information
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">City</div>
                            <div class="fw-semibold fs-6">
                                {{ $warehouse->city ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Status</div>
                            <span class="badge px-3 py-2
                                {{ $warehouse->status === 'active'
                                    ? 'bg-success'
                                    : 'bg-secondary' }}">
                                {{ ucfirst($warehouse->status) }}
                            </span>
                        </div>

                        <div class="col-md-12">
                            <div class="text-muted small mb-1">Address</div>
                            <div class="fw-semibold">
                                {{ $warehouse->address ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Manager</div>
                            <div class="fw-semibold">
                                {{ $warehouse->manager_name ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Phone</div>
                            <div class="fw-semibold">
                                {{ $warehouse->phone ?? '—' }}
                            </div>
                        </div>

                        @if($warehouse->notes)
                        <div class="col-md-12">
                            <div class="text-muted small mb-1">Notes</div>
                            <div class="p-3 bg-light rounded">
                                {{ $warehouse->notes }}
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
@endsection
