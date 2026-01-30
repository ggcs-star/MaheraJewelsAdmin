@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Edit Warehouse</h2>
            <small class="text-muted">Update storage location details</small>
        </div>
      
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

    <form method="POST" action="{{ admin_route('warehouses.update', $warehouse) }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">

                <div class="row">

                    {{-- Code --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warehouse Code *</label>
                        <input type="text" name="code" class="form-control"
                               value="{{ old('code', $warehouse->code) }}" required>
                    </div>

                    {{-- Name --}}
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Warehouse Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $warehouse->name) }}" required>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $warehouse->address) }}</textarea>
                    </div>

                    {{-- City --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $warehouse->city) }}">
                    </div>

                    {{-- State --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $warehouse->state) }}">
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control"
                               value="{{ old('pincode', $warehouse->pincode) }}">
                    </div>

                    {{-- Country --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ old('country', $warehouse->country) }}">
                    </div>

                    {{-- Manager Name --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Manager Name</label>
                        <input type="text" name="manager_name" class="form-control"
                               value="{{ old('manager_name', $warehouse->manager_name) }}">
                    </div>

                    {{-- Manager Phone --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Manager Phone</label>
                        <input type="text" name="manager_phone" class="form-control"
                               value="{{ old('manager_phone', $warehouse->manager_phone) }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $warehouse->status) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $warehouse->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $warehouse->notes) }}</textarea>
                    </div>

                </div>

            </div>

            <div class="card-footer text-end">
                   <a href="{{ admin_route('warehouses.index') }}" class="btn btn-outline-secondary">
                        Back
                    </a>
                <button type="submit" class="btn btn-primary">
                    Update Warehouse
                </button>
              
            </div>
        </div>
    </form>

</div>
@endsection
