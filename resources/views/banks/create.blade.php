@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h2>Add Bank</h2>
        <a href="{{ admin_route('banks.index') }}" class="btn btn-secondary">
            ← Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ admin_route('banks.store') }}">
                @csrf

                {{-- Bank Name --}}
                <div class="mb-3">
                    <label class="form-label">Bank Name</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="e.g. State Bank of India">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bank Code --}}
                <div class="mb-3">
                    <label class="form-label">Bank Code</label>
                    <input type="text"
                           name="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="e.g. SBI">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror">
                        <option value="">Select status</option>
                        <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
    Active
</option>
<option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
    Inactive
</option>

                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        Save Bank
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection