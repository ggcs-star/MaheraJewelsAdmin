@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h2>Edit Bank</h2>
        <a href="{{ admin_route('banks.index') }}" class="btn btn-secondary">
            ← Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ admin_route('banks.update', $bank) }}">
                @csrf
                @method('PUT')

                {{-- Bank Name --}}
                <div class="mb-3">
                    <label class="form-label">Bank Name</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $bank->name) }}">
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
                           value="{{ old('code', $bank->code) }}">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror">
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
                    <button class="btn btn-success px-4">
                        Update Bank
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection