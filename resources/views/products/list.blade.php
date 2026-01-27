@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Products</h2>
        <a href="{{ admin_route('products.push') }}" class="btn btn-primary">
            + Add Product
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Empty State --}}
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h4 class="text-muted mb-3">No Products Yet</h4>
            <p class="text-muted mb-4">
                Start by pushing your inventory products to marketplaces like Website, Flipkart, or Amazon.
            </p>
            <a href="{{ admin_route('products.push') }}" class="btn btn-primary btn-lg">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Push Your First Product
            </a>
        </div>
    </div>

    {{-- Future: Products listing table will go here --}}
    {{-- 
    <div class="card mt-4">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Platforms</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Products will be listed here -->
                </tbody>
            </table>
        </div>
    </div>
    --}}

</div>
@endsection
