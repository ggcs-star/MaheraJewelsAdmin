@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ admin_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ admin_route('products.index') }}">Inventory</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1">Product Details</h2>
            <div class="text-muted">Product ID: #{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }} | SKU: {{ $product->sku }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ admin_route('products.edit', $product) }}" class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <a href="{{ admin_route('products.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    {{-- QUICK STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-{{ $product->status === 'active' ? 'check-circle' : 'pause-circle' }} text-{{ $product->status === 'active' ? 'success' : 'secondary' }} fa-lg"></i>
                        </div>
                        <div class="text-muted small">Status</div>
                    </div>
                    <div class="fw-bold fs-5">{{ ucfirst($product->status) }}</div>
                    <div class="small text-muted mt-1">Product status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-eye text-primary fa-lg"></i>
                        </div>
                        <div class="text-muted small">Visibility</div>
                    </div>
                    <div class="fw-bold fs-5 text-capitalize">{{ $product->visibility }}</div>
                    <div class="small text-muted mt-1">Visibility status</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-boxes text-info fa-lg"></i>
                        </div>
                        <div class="text-muted small">Total Variants</div>
                    </div>
                    <div class="fw-bold fs-5">{{ $product->variants->count() }}</div>
                    <div class="small text-muted mt-1">Product variants</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                            <i class="fas fa-rupee-sign text-warning fa-lg"></i>
                        </div>
                        <div class="text-muted small">Total Inventory Value</div>
                    </div>
                    <div class="fw-bold fs-5">₹{{ number_format($product->variants->sum('total_price'), 2) }}</div>
                    <div class="small text-muted mt-1">Based on variants</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- LEFT PROFILE CARD --}}
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-cube text-primary me-2"></i>
                        Product Overview
                    </h5>
                </div>
                <div class="card-body text-center pt-0">

                    @if($product->image_url)
                        <div class="mb-3 border rounded overflow-hidden" style="height: 200px;">
                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                 class="img-fluid h-100 w-100 object-fit-cover"
                                 alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 120px; height: 120px; font-size: 48px; font-weight: 600;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                                <div class="rounded-circle bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}"
                                     style="width: 16px; height: 16px;"></div>
                            </div>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">{{ $product->name }}</h4>

                    <div class="text-muted mb-3">
                        <i class="fas fa-barcode me-1"></i>SKU: {{ $product->sku }}
                    </div>

                    <div class="mb-4">
                        <span class="badge rounded-pill px-4 py-2 fs-6
                            {{ $product->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            <i class="fas fa-{{ $product->status === 'active' ? 'check' : 'pause' }} me-1"></i>
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>

                    {{-- QUICK INFO --}}
                    <div class="border-top pt-4 mt-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-folder text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Category</div>
                                <div class="fw-semibold">
                                    {{ $product->category?->name ?? 'N/A' }}
                                    @if($product->category?->parent)
                                        <span class="text-muted">→ {{ $product->category->parent->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-truck text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Supplier</div>
                                <div class="fw-semibold">{{ $product->supplier?->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-tag text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Brand</div>
                                <div class="fw-semibold">{{ $product->brand ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-rupee-sign text-primary"></i>
                            </div>
                            <div class="text-start">
                                <div class="text-muted small">Base Selling Price</div>
                                <div class="fw-semibold">₹{{ number_format($product->base_selling_price, 2) }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT DETAILS --}}
        <div class="col-xl-8 col-lg-8">
            <div class="row g-4">

                {{-- BASIC INFORMATION CARD --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Basic Information
                            </h6>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-address-card me-1"></i>Details
                            </span>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-tag text-muted me-2 small"></i>
                                            <div class="text-muted small">Product Name</div>
                                        </div>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-hashtag text-muted me-2 small"></i>
                                            <div class="text-muted small">Slug</div>
                                        </div>
                                        <div class="fw-semibold">{{ $product->slug }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-barcode text-muted me-2 small"></i>
                                            <div class="text-muted small">SKU</div>
                                        </div>
                                        <div class="fw-semibold">{{ $product->sku }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-folder text-muted me-2 small"></i>
                                            <div class="text-muted small">Category</div>
                                        </div>
                                        <div class="fw-semibold">
                                            {{ $product->category?->name ?? 'N/A' }}
                                            @if($product->category?->parent)
                                                <span class="text-muted">→ {{ $product->category->parent->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-truck text-muted me-2 small"></i>
                                            <div class="text-muted small">Supplier</div>
                                        </div>
                                        <div class="fw-semibold">{{ $product->supplier?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- PROCUREMENT DETAILS --}}
<div class="col-12">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <i class="fas fa-warehouse text-primary me-2"></i>
                Procurement Details
            </h6>
        </div>

        <div class="card-body pt-0">
            <div class="row g-3">

                {{-- Warehouse --}}
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted small mb-1">Warehouse (City)</div>
                        <div class="fw-semibold">
                            {{ $product->warehouse
                                ? $product->warehouse->city . ' — ' . $product->warehouse->name
                                : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Expected Delivery --}}
                <div class="col-md-4">
                    <div class="p-3 border rounded">
                        <div class="text-muted small mb-1">Expected Delivery</div>
                        <div class="fw-semibold">
                            {{ $product->expected_delivery_date
                                ? \Carbon\Carbon::parse($product->expected_delivery_date)->format('d M Y')
                                : 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Payment Terms --}}
                <div class="col-md-4">
                    <div class="p-3 border rounded">
                        <div class="text-muted small mb-1">Payment Terms</div>
                        <div class="fw-semibold text-capitalize">
                            {{ $product->payment_terms ?? 'N/A' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

                {{-- DESCRIPTION CARD --}}
                @if($product->description || $product->short_description)
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-align-left text-primary me-2"></i>
                                Description
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            @if($product->short_description)
                            <div class="mb-3">
                                <div class="text-muted small mb-2">Short Description</div>
                                <div class="p-3 border rounded bg-light bg-opacity-50">
                                    {{ $product->short_description }}
                                </div>
                            </div>
                            @endif
                            @if($product->description)
                            <div>
                                <div class="text-muted small mb-2">Full Description</div>
                                <div class="p-4 border rounded bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <i class="fas fa-quote-left text-muted me-2 mt-1"></i>
                                        <div>{{ $product->description }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- PRICING INFORMATION CARD --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                Pricing Information
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shopping-cart text-muted me-2 small"></i>
                                        <span class="text-muted small">Cost Price</span>
                                    </div>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-warning ps-3 fs-5">
                                    ₹{{ number_format($product->cost_price, 2) }}
                                </div>
                            </div>

                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tag text-muted me-2 small"></i>
                                        <span class="text-muted small">Base Selling Price</span>
                                    </div>
                                </div>
                                <div class="fw-semibold p-2 border-start border-3 border-success ps-3 fs-5">
                                    ₹{{ number_format($product->base_selling_price, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SETTINGS & SEO CARD --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-cog text-primary me-2"></i>
                                Settings & SEO
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-star text-muted me-2 small"></i>
                                            <div class="text-muted small">Featured</div>
                                        </div>
                                        <div class="fw-semibold">
                                            @if($product->is_featured)
                                                <span class="badge bg-warning text-white">
                                                    <i class="fas fa-star me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-star me-1"></i>No
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-fire text-muted me-2 small"></i>
                                            <div class="text-muted small">Top Selling</div>
                                        </div>
                                        <div class="fw-semibold">
                                            @if($product->is_top_selling)
                                                <span class="badge bg-danger text-white">
                                                    <i class="fas fa-fire me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-fire me-1"></i>No
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($product->meta_title)
                                <div class="col-12">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-heading text-muted me-2 small"></i>
                                            <div class="text-muted small">Meta Title</div>
                                        </div>
                                        <div class="fw-semibold small">{{ $product->meta_title }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-boxes text-primary me-2"></i>
                                Product Variants
                            </h6>
                            <span class="badge bg-info text-white">
                                {{ $product->variants->count() }} Variant(s)
                            </span>
                        </div>
                        <div class="card-body pt-0 p-0">
                            @if($product->variants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr class="text-center align-middle">
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>SKU Suffix</th>
                                            <th>Image</th>
                                            <th>Quantity</th>
                                            <th>Cost Price</th>
                                            <th>Selling Price</th>
                                            <th>Total Value</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $index => $variant)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                           <td>
    <span class="badge bg-primary bg-opacity-10 text-primary">
        {{ $variant->variant->name }}
    </span>
</td>
<td class="fw-semibold">{{ $variant->value->value }}</td>

                                            <td>
                                                <code class="small">{{ $variant->sku_suffix ?? '—' }}</code>
                                            </td>
                                            <td class="text-center">
                                                @if($variant->image_url)
                                                    <img src="{{ asset('storage/' . $variant->image_url) }}"
                                                         width="50" height="50"
                                                         class="img-thumbnail rounded"
                                                         alt="{{ $variant->variant_value }}">
                                                @else
                                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-white">{{ $variant->quantity }}</span>
                                            </td>
                                            <td class="text-end">₹{{ number_format($variant->purchase_price, 2) }}</td>
                                            <td class="text-end">₹{{ number_format($variant->selling_price, 2) }}</td>
                                            <td class="text-end fw-bold text-success">
                                                ₹{{ number_format($variant->total_price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $variant->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($variant->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end fw-bold">Totals:</td>
                                            <td class="text-center fw-bold">
                                                <span class="badge bg-primary">{{ $product->variants->sum('quantity') }}</span>
                                            </td>
                                            <td class="text-end fw-bold">
                                                ₹{{ number_format($product->variants->sum('purchase_price'), 2) }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                ₹{{ number_format($product->variants->sum('selling_price'), 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-success fs-5">
                                                ₹{{ number_format($product->variants->sum('total_price'), 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <div>No variants added for this product</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
    </div>

</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    border-color: #e0e0e0;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.border-start {
    border-left-width: 4px !important;
}
.small {
    font-size: 0.85rem;
}
.object-fit-cover {
    object-fit: cover;
}
</style>
@endsection
