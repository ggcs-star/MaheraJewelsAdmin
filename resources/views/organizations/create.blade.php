@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Add Organization</h2>
            <small class="text-muted">Create new organization</small>
        </div>
        <a href="{{ admin_route('organizations.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    {{-- GLOBAL ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ admin_route('organizations.store') }}"
          enctype="multipart/form-data">
        @csrf

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mobile --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text"
                               name="mobile"
                               class="form-control"
                               value="{{ old('mobile') }}">
                    </div>

                    {{-- Website --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Website</label>
                        <input type="url"
                               name="website"
                               class="form-control"
                               value="{{ old('website') }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="is_active"
                                class="form-select @error('is_active') is-invalid @enderror"
                                required>
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="2">{{ old('address') }}</textarea>
                    </div>

                    {{-- City --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text"
                               name="city"
                               class="form-control"
                               value="{{ old('city') }}">
                    </div>

                    {{-- State --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text"
                               name="state"
                               class="form-control"
                               value="{{ old('state') }}">
                    </div>

                    {{-- Country --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text"
                               name="country"
                               class="form-control"
                               value="{{ old('country') }}">
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Pincode</label>
                        <input type="text"
                               name="pincode"
                               class="form-control"
                               value="{{ old('pincode') }}">
                    </div>

                    {{-- Logo --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file"
                               name="logo"
                               class="form-control @error('logo') is-invalid @enderror">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary px-4">
                    Save Organization
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
