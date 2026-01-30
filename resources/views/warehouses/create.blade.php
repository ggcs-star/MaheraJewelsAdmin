@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Add Warehouse</h2>
            <small class="text-muted">Create new storage location</small>
        </div>
        <a href="{{ admin_route('warehouses.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ admin_route('warehouses.store') }}">
        @csrf

        <div class="card">
            <div class="card-body">

                <div class="row">

                    {{-- Code --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warehouse Code *</label>
                        <input type="text" name="code" class="form-control"
                               value="{{ old('code') }}" required>
                    </div>

                    {{-- Name --}}
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Warehouse Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name') }}" required>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                    </div>

                    {{-- City --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city') }}">
                    </div>

                    {{-- State --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state') }}">
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control"
                               value="{{ old('pincode') }}">
                    </div>

                    {{-- Country --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ old('country') }}">
                    </div>

                    {{-- Manager Name --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Manager Name</label>
                        <input type="text" name="manager_name" class="form-control"
                               value="{{ old('manager_name') }}">
                    </div>

                    {{-- Manager Phone --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Manager Phone</label>
                        <input type="text" name="manager_phone" class="form-control"
                               value="{{ old('manager_phone') }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">
                    Save Warehouse
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
