@extends('layouts.admin')
@push('scripts')
<script src="{{ asset('assets/js/admin/product.js') }}"></script>
@endpush

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h2>Inventory</h2>
        <a href="{{ admin_route('products.create') }}" class="btn btn-primary">
            + Add Inventory
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="card mb-4" id="productFilterForm">
        <div class="card-body row g-2 align-items-end">

            <div class="col-md-3">
                <div class="position-relative">
                    <input type="text"
                           name="search"
                           id="productSearch"
                           class="form-control pe-5"
                           placeholder="Search name / SKU / slug"
                           value="{{ request('search') }}"
                           autocomplete="off">
                    <span id="clearProductSearch"
                          class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                          style="cursor:pointer; display:none;">
                        ✕
                    </span>
                </div>
            </div>

            <div class="col-md-2">
                <select name="category_id" class="form-control auto-submit">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="supplier_id" class="form-control auto-submit">
                    <option value="">All Suppliers</option>
                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="visibility" class="form-control auto-submit">
                    <option value="">Visibility</option>
                    <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-control auto-submit">
                    <option value="">Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button"
                    id="openProductFilterSidebar"
                    class="btn btn-secondary w-100"
                    style="height:38px">
                    Filter
                </button>
            </div>

        </div>
    </form>

  <form method="POST" action="{{ admin_route('products.bulk-delete') }}" id="productBulkDeleteForm">
    @csrf
    @method('DELETE')
        <button type="button" id="bulkDeleteBtn" class="btn btn-danger mb-3" disabled>
            Delete Selected
        </button>
</form>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAllProducts">
                            </th>
                            <th>Id</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Warehouse</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                   @forelse ($products as $product)
                        <tr style="cursor:pointer" onclick="window.location='{{ admin_route('products.show', $product->id) }}'">
                          <td onclick="event.stopPropagation()">
                            <input type="checkbox"
                                class="product-row-checkbox"
                                name="ids[]"
                                value="{{ $product->id }}"
                                form="productBulkDeleteForm">

                            </td>
                            <td>{{ $product->id }}</td>
 <td>
    @php
        $images = [];

        if (!empty($product->gallery_images) && is_array($product->gallery_images)) {
            $images = $product->gallery_images;
        } elseif ($product->image_url) {
            $images = [$product->image_url];
        }
    @endphp

    @if(count($images))
        <img
            src="{{ asset('storage/'.$images[0]) }}"
            class="img-thumbnail product-image-preview"
            data-images='@json(array_map(fn($i) => asset("storage/".$i), $images))'
            style="width:50px;height:50px;object-fit:cover;cursor:zoom-in;"
            onclick="event.stopPropagation()"
        >
    @else
        <span class="text-muted small">No Image</span>
    @endif
</td>
             
                            <td>
                                <a href="{{ admin_route('products.show', $product->id) }}"
                                   class="text-decoration-none fw-semibold">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>{{ $product->supplier?->name }}</td>
                            <td>
                                @if($product->warehouse)
                                    {{ $product->warehouse->name }}
                                    
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>₹{{ number_format($product->variants->sum('total_price'), 2) }}</td>
                            <td>
                                <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td onclick="event.stopPropagation()">
                                

                                <a href="{{ admin_route('products.edit', $product->id) }}"
                                class="btn btn-sm btn-primary">Edit</a>

                                 <a href="{{ admin_route('products.invoice.view', $product->id) }}"
       class="btn btn-sm btn-success">
        Invoice
    </a>

                                <form action="{{ admin_route('products.destroy', $product->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this product?')">
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
                            <td colspan="10" class="text-center text-muted">
                                No products found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                <strong>Showing:</strong> {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
            </div>
            <div>
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @else
        <div class="mt-3 text-muted small">
            Showing {{ $products->count() }} product(s)
        </div>
    @endif
<div id="productFilterSidebar" class="filter-sidebar">
    <div class="filter-sidebar-header">
        <h5 class="mb-0">Advanced Filter</h5>
        <button type="button" id="closeProductFilterSidebar">✕</button>
    </div>

    <div class="filter-sidebar-body">

        <div class="mb-3">
            <label class="form-label">Field</label>
            <select id="productAdvField" class="form-control">
                <option value="name">Name</option>
                <option value="sku">SKU</option>
                <option value="cost_price">Price</option>
                <option value="status">Status</option>
                <option value="visibility">Visibility</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Condition</label>
           <select id="productAdvCondition" class="form-control">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equals</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
                <option value=">">Greater Than</option>
                <option value="<">Less Than</option>
            </select>

        </div>

        <div class="mb-3">
            <label class="form-label">Value</label>
            <input type="text" id="productAdvValue" class="form-control">
        </div>

        <button type="button"
            id="applyProductAdvancedFilter"
            class="btn btn-primary w-100">
            Apply Filter
        </button>

    </div>
</div>

</div>
<div id="imagePreviewModal" class="image-preview-modal">
    <span class="modal-close">✕</span>

    <div class="modal-content">
        <!-- LEFT ARROW -->
        <span class="modal-nav left">‹</span>

        <!-- IMAGE -->
        <img id="imagePreviewModalImg" src="" alt="Preview">

        <!-- RIGHT ARROW -->
        <span class="modal-nav right">›</span>
    </div>
</div>


@endsection
