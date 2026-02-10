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
                    <div class="mb-3 position-relative">
                        <label class="form-label">
                            Bank Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. State Bank of India">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


               <div class="mb-3 position-relative">
                <label class="form-label">
                    Bank Code <span class="text-danger">*</span>
                </label>

                <input type="text"
                    name="code"
                    value="{{ old('code') }}"
                    class="form-control @error('code') is-invalid @enderror"
                    placeholder="e.g. SBI">

                @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

               <div class="mb-4 position-relative">
    <label class="form-label">
        Status <span class="text-danger">*</span>
    </label>

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
        <div class="invalid-feedback">
            {{ $message }}
        </div>
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