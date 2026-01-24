@extends('layouts.admin')

@section('content')
    <div class="container">

        <h2 class="mb-3">Suppliers List</h2>

        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name / company / phone"
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="manufacturer" {{ request('type') == 'manufacturer' ? 'selected' : '' }}>
                        Manufacturer
                    </option>
                    <option value="distributor" {{ request('type') == 'distributor' ? 'selected' : '' }}>
                        Distributor
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($supplier->type) }}
                                </span>
                            </td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->company_name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>
                                @if($supplier->commission_type === 'percentage')
                                    {{ $supplier->commission_value }}%
                                @else
                                    ₹{{ $supplier->commission_value }}
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $supplier->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ admin_route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-primary">
                                    Edit
                                </a>

                                <form action="{{ admin_route('suppliers.destroy', $supplier) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this supplier?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No suppliers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $suppliers->withQueryString()->links() }}

    </div>
@endsection