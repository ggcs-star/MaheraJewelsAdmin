@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-user-edit text-primary me-2"></i> Edit Customer
            </h4>
            <small class="text-muted">Update customer information</small>
        </div>
        <a href="{{ admin_route('customers.index') }}" class="btn btn-outline-secondary">
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
          action="{{ admin_route('customers.update', $customer) }}"
          novalidate>
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">

                    {{-- NAME --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $customer->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $customer->email) }}"
                               placeholder="example@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MOBILE --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Mobile <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="mobile"
                               class="form-control @error('mobile') is-invalid @enderror"
                               value="{{ old('mobile', $customer->mobile) }}"
                               maxlength="10"
                               pattern="[6-9][0-9]{9}"
                               inputmode="numeric"
                               placeholder="10 digit mobile number"
                               required>
                        <small class="text-muted">Must be 10 digits (India)</small>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="address_line_1"
                                  rows="2"
                                  class="form-control @error('address_line_1') is-invalid @enderror"
                                  required>{{ old('address_line_1', $customer->address_line_1) }}</textarea>
                        @error('address_line_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- ADDRESS LINE 2 (OPTIONAL) --}}
<div class="col-md-12">
    <label class="form-label fw-semibold">
        Address Line 2 <span class="text-muted">(Optional)</span>
    </label>
    <textarea name="address_line_2"
              class="form-control @error('address_line_2') is-invalid @enderror"
              rows="2">{{ old('address_line_2', $customer->address_line_2) }}</textarea>
    @error('address_line_2')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                    {{-- CITY --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            City <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city', $customer->city) }}"
                               required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATE --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            State <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="state"
                               class="form-control @error('state') is-invalid @enderror"
                               value="{{ old('state', $customer->state) }}"
                               required>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- COUNTRY --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Country <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="country"
                               class="form-control @error('country') is-invalid @enderror"
                               value="{{ old('country', $customer->country ?? 'India') }}"
                               required>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ZIP CODE --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Zip Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="zip_code"
                               class="form-control @error('zip_code') is-invalid @enderror"
                               value="{{ old('zip_code', $customer->zip_code) }}"
                               maxlength="6"
                               pattern="[0-9]{5,6}"
                               inputmode="numeric"
                               required>
                        @error('zip_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="is_active"
                                class="form-select @error('is_active') is-invalid @enderror"
                                required>
                            <option value="1" {{ old('is_active', $customer->is_active) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('is_active', $customer->is_active) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Update Customer
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
