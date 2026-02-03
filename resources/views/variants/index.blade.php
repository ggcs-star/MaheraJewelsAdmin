@extends('layouts.admin.admin-settings')

@section('settings-content')
<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">🧩 Variants Master</h4>
        <small class="text-muted">
            Create generic variant types (Color, Size, Weight, Dimension) and manage their values
        </small>
    </div>

    {{-- ADD VARIANT --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold">Add Variant Type</div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.variants.store') }}" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Variant Name</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Color / Size / Weight / Dimension" required>
                    <small class="text-muted">Logical category name</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" class="form-control"
                           placeholder="color / size / weight" required>
                    <small class="text-muted">Unique system key</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Input Type</label>
                    <select name="input_type" class="form-select" required>
                        <option value="color">Color Picker</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="dimension">Height × Width</option>
                    </select>
                    <small class="text-muted">Controls value input UI</small>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- VARIANT LIST --}}
    @forelse($variants as $variant)
        <div class="card mb-3 shadow-sm">

            {{-- VARIANT HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $variant->name }}</strong>
                    <span class="badge bg-secondary ms-2">
                        {{ strtoupper($variant->input_type) }}
                    </span>
                </div>

                {{-- DELETE VARIANT --}}
                <form method="POST"
                      action="{{ route('admin.variants.destroy', $variant) }}"
                      onsubmit="return confirm('Delete this variant and all its values?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>

            <div class="card-body">

                {{-- ADD VALUE --}}
                <form method="POST"
                      action="{{ route('admin.variants.values.store', $variant) }}"
                      class="row g-2 align-items-end mb-3">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Value</label>
                        <input type="text" name="value" class="form-control"
                               placeholder="Red / XL / 1kg" required>
                    </div>

                    {{-- EXTRA FIELD --}}
                    @if($variant->input_type === 'color')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Color</label>
                            <input type="color" name="extra"
                                   class="form-control form-control-color">
                        </div>
                    @elseif($variant->input_type === 'dimension')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Dimension</label>
                            <input type="text" name="extra" class="form-control"
                                   placeholder="150x120">
                        </div>
                    @endif

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Add Value</button>
                    </div>
                </form>

                {{-- VALUE LIST --}}
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="30%">Value</th>
                                <th width="40%">Extra</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variant->values as $value)
                                <tr>
                                    <td>{{ $value->value }}</td>

                                    <td>
                                        @if($variant->input_type === 'color' && $value->extra)
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="
                                                    width:22px;
                                                    height:22px;
                                                    background:{{ $value->extra }};
                                                    border:1px solid #ccc;
                                                    border-radius:4px;
                                                    display:inline-block;">
                                                </span>
                                                <small class="text-muted">
                                                    {{ $value->extra }}
                                                </small>
                                            </div>
                                        @else
                                            {{ $value->extra ?? '—' }}
                                        @endif
                                    </td>

                                    {{-- DELETE VALUE --}}
                                    <td class="text-center">
                                        <form method="POST"
                                              action="{{ route('admin.variants.values.destroy', $value) }}"
                                              onsubmit="return confirm('Delete this value?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No values added yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-light text-center">
            No variants created yet
        </div>
    @endforelse

</div>
@endsection
