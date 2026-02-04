@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>GST & Taxes</h2>
        <a href="{{ admin_route('taxes.create') }}" class="btn btn-primary">
            + Add Tax
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Rate</th>
            <th>Type</th>
            <th>Status</th>
            <th width="150">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($taxes as $tax)
            <tr>
                <td>{{ $tax->name }}</td>
                <td>{{ $tax->rate }}</td>
                <td>{{ ucfirst($tax->type) }}</td>
                <td>
                    <span class="badge bg-{{ $tax->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($tax->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ admin_route('taxes.edit', $tax->id) }}"
                       class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ admin_route('taxes.destroy', $tax->id) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this tax?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No taxes added yet
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
