@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4" style="background: #f4f7fc; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold" style="font-size: 1.6rem; color: #0a1e2f; letter-spacing: -0.02em;">
                <i class="fas fa-boxes me-2" style="color: #8B2452;"></i> Master Inventory Dashboard
            </h1>
            <p class="text-muted" style="font-size: 0.85rem; margin-top: 2px;">
                Consolidated master inventory across offline stores and website
            </p>
        </div>
        <div>
            <span class="badge bg-success px-3 py-2 rounded-5 me-2" style="font-size: 0.7rem; font-weight: 600;">
                <i class="fas fa-circle text-white me-1" style="font-size: 0.4rem;"></i> Live Sync
            </span>
            <span class="badge bg-white text-dark px-3 py-2 rounded-5 border" style="font-size: 0.7rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                <i class="far fa-clock me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </span>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex gap-2 flex-wrap align-items-center" style="background: white; padding: 6px 12px; border-radius: 60px; border: 1px solid #e2ecf5; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
            <span class="text-muted" style="font-size: 0.7rem; font-weight: 600; margin-right: 8px;">
                <i class="fas fa-filter me-1"></i> VIEW:
            </span>
            <a href="?filter=all" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter', 'all') == 'all' ? 'bg-[#8B2452] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.75rem;">
                📊 All Channels
            </a>
            <a href="?filter=website" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'website' ? 'bg-[#1a56db] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.75rem;">
                🌐 Website
            </a>
            <a href="?filter=offline" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'offline' ? 'bg-[#0f7b4b] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.75rem;">
                🏪 Offline
            </a>
            <a href="?filter=low" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'low' ? 'bg-[#d97706] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.75rem;">
                ⚠️ Low Stock
            </a>
            <a href="?filter=out" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'out' ? 'bg-[#dc3545] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.75rem;">
                ❌ Out of Stock
            </a>
        </div>
    </div>

<div class="mb-4">
    <div class="card border-0 shadow-sm rounded-4 p-3" style="background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
        <form method="GET" action="{{ url('admin/inventory/dashboard') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label style="font-size: 0.65rem; font-weight: 600; color: #547087; text-transform: uppercase; letter-spacing: 0.5px;">Search</label>
                <div class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm rounded-4" 
                           placeholder="Search by Product, SKU..." 
                           value="{{ request('search') }}" 
                           style="border-color: #e2ecf5; font-size: 0.85rem; padding: 8px 16px;">
                    <button type="submit" class="btn btn-sm btn-primary rounded-4 px-4" style="background: #8B2452; border: none; font-weight: 600;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-2">
                <label style="font-size: 0.65rem; font-weight: 600; color: #547087; text-transform: uppercase; letter-spacing: 0.5px;">Brands</label>
                <select name="brand" class="form-select form-select-sm rounded-4" style="border-color: #e2ecf5; font-size: 0.85rem;">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label style="font-size: 0.65rem; font-weight: 600; color: #547087; text-transform: uppercase; letter-spacing: 0.5px;">Categories</label>
                <select name="category" class="form-select form-select-sm rounded-4" style="border-color: #e2ecf5; font-size: 0.85rem;">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label style="font-size: 0.65rem; font-weight: 600; color: #547087; text-transform: uppercase; letter-spacing: 0.5px;">Suppliers</label>
                <select name="supplier" class="form-select form-select-sm rounded-4" style="border-color: #e2ecf5; font-size: 0.85rem;">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label style="font-size: 0.65rem; font-weight: 600; color: #547087; text-transform: uppercase; letter-spacing: 0.5px;">Stock Status</label>
                <select name="stock_status" class="form-select form-select-sm rounded-4" style="border-color: #e2ecf5; font-size: 0.85rem;">
                    <option value="">Stock Status (All)</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>✅ In Stock</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>⚠️ Low Stock</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>❌ Out of Stock</option>
                </select>
            </div>

            <div class="col-md-1">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary rounded-4 px-3" style="background: #8B2452; border: none; font-weight: 600;">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ url('admin/inventory/dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-4 px-3" style="font-weight: 600;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #faf5f8 100%); border-left: 4px solid #8B2452;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Products</span>
                        <h3 class="fw-bold mb-0" style="color: #0a1e2f; font-size: 1.8rem;">{{ $summary['total_products'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">active SKUs</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #f3e8f0; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-boxes" style="color: #8B2452; font-size: 1.4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f0f8f3 100%); border-left: 4px solid #0f7b4b;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Inventory</span>
                        <h3 class="fw-bold mb-0" style="color: #0a1e2f; font-size: 1.8rem;">{{ $summary['total_stock'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">units</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #e8f3ed; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-warehouse" style="color: #0f7b4b; font-size: 1.4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #eef4fb 100%); border-left: 4px solid #1a56db;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Website Stock</span>
                        <h3 class="fw-bold mb-0" style="color: #1a56db; font-size: 1.8rem;">{{ $summary['total_website'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">live</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #e8f0fe; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-globe" style="color: #1a56db; font-size: 1.4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #fef5ec 100%); border-left: 4px solid #b85e00;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Offline Stock</span>
                        <h3 class="fw-bold mb-0" style="color: #b85e00; font-size: 1.8rem;">{{ $summary['total_offline'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">store</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #fff4e5; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store" style="color: #b85e00; font-size: 1.4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="card border-0 shadow-sm rounded-4" style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden;">
    <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom: 2px solid #f0f6fa;">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold" style="color: #0a1e2f;">
                <i class="fas fa-list me-2" style="color: #8B2452;"></i> Inventory Allocation Catalog
            </h6>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark px-3 py-2 rounded-5 border" style="font-weight: 600;">{{ $totalItems }} items</span>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size: 0.7rem; color: #7c9eb2;">Show:</span>
                    <select id="perPageSelect" class="form-select form-select-sm" style="width: 70px; font-size: 0.75rem; border-radius: 30px; border-color: #e2ecf5;" onchange="window.location.href='?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), per_page: this.value, page: 1}).toString()">
                        <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead style="background: #f8fcff; border-bottom: 2px solid #e9f0f5;">
                    <tr>
                        <th class="px-3 py-3" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 50px;">Image</th>
                        <th class="px-3 py-3" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; min-width: 200px;">Product Details</th>
                        <th class="px-3 py-3" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; min-width: 180px;">Variant</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; min-width: 160px;">Channel Stock</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #0a1e2f; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 70px;">Total</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #0f7b4b; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 80px;">Available</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #dc3545; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 70px;">Sold</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 80px;">Sync</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 100px;">Status</th>
                        <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; width: 90px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedData as $item)
                        @php
                            $filter = request('filter', 'all');
                            $channelStock = '';
                            if ($filter == 'all' || $filter == '') {
                                $channelStock = '🏪 ' . $item['offline_available'] . ' | 🌐 ' . $item['website_available'];
                            } elseif ($filter == 'website') {
                                $channelStock = '🌐 ' . $item['website_available'];
                            } elseif ($filter == 'offline') {
                                $channelStock = '🏪 ' . $item['offline_available'];
                            }

                            $availableStock = $item['final_stock'];
                            if ($filter == 'website') {
                                $availableStock = $item['website_available'];
                            } elseif ($filter == 'offline') {
                                $availableStock = $item['offline_available'];
                            }

                            $soldDisplay = '';
                            if ($filter == 'website' || $filter == 'all') {
                                $soldDisplay = $item['website_sold'];
                            }
                            if ($filter == 'offline' || $filter == 'all') {
                                if ($filter == 'all') {
                                    $soldDisplay = $item['website_sold'] + $item['offline_sold'];
                                } else {
                                    $soldDisplay = $item['offline_sold'];
                                }
                            }

                            $statusClass = 'In Stock';
                            $statusColor = '#2e7d32';
                            $statusBg = '#e8f5e9';
                            if ($item['final_stock'] == 0) {
                                $statusClass = 'Out of Stock';
                                $statusColor = '#c62828';
                                $statusBg = '#fce4ec';
                            } elseif ($item['final_stock'] <= 5) {
                                $statusClass = 'Low Stock';
                                $statusColor = '#e65100';
                                $statusBg = '#fff8e1';
                            }
                        @endphp
                        <tr style="border-bottom: 1px solid #f0f6fa; transition: background 0.15s;">
                            <td class="px-3 py-3">
                                @if($item['image_url'])
                                    <img src="{{ \App\Helpers\S3Helper::url($item['image_url']) }}" 
                                         style="width: 45px; height: 45px; object-fit: cover; border-radius: 10px; border: 1px solid #e9f0f5;">
                                @else
                                    <div style="width: 45px; height: 45px; background: #f1f7fd; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #e9f0f5;">
                                        <i class="fas fa-box" style="color: #7c9eb2; font-size: 1.2rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-semibold" style="color: #0a1e2f; font-size: 0.85rem;">
                                    {{ $item['product_name'] }}
                                </div>
                                <div style="font-size: 0.65rem; color: #7c9eb2; margin-top: 2px;">
                                    <code>{{ $item['sku'] }}</code>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div style="font-size: 0.85rem; color: #0a1e2f;">
                                    {{ $item['variant_name'] }}
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center" style="font-size: 0.8rem; font-weight: 500; color: #0a1e2f;">
                                {{ $channelStock }}
                            </td>
                            <td class="px-3 py-3 text-center fw-bold" style="font-size: 1rem;">{{ $item['total_stock'] }}</td>
                            <td class="px-3 py-3 text-center fw-bold" style="color: #0f7b4b; font-size: 1rem;">{{ max(0, $availableStock) }}</td>
                            <td class="px-3 py-3 text-center fw-bold" style="color: #dc3545; font-size: 1rem;">{{ $soldDisplay }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="badge bg-success rounded-5 px-3 py-2" style="font-size: 0.65rem; font-weight: 600;">
                                    <i class="fas fa-check-circle me-1"></i> Synced
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="badge px-3 py-2 rounded-5" style="font-size: 0.7rem; font-weight: 600; background: {{ $statusBg }}; color: {{ $statusColor }};">
                                    @if($statusClass == 'In Stock') ✅ @endif
                                    {{ $statusClass }}
                                </span>
                            </td>
                          <td class="px-3 py-3 text-center">
                            <span class="btn btn-sm btn-outline-secondary rounded-5 px-3" 
                                style="font-size: 0.7rem; font-weight: 600; border-width: 1.5px; cursor: not-allowed; opacity: 0.6; pointer-events: none;">
                                Manage <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-box-open" style="font-size: 2.5rem; color: #d4e2f0;"></i>
                                <p class="text-muted mt-2" style="font-size: 0.9rem;">No products found in inventory</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div style="font-size: 0.8rem; color: #7c9eb2;">
        Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $totalItems) }} of {{ $totalItems }} entries
    </div>
    <nav>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link rounded-5" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $currentPage - 1])) }}" style="border-radius: 30px; color: #8B2452;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>

            @php
                $totalPages = ceil($totalItems / $perPage);
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
            @endphp

            @if($startPage > 1)
                <li class="page-item">
                    <a class="page-link rounded-5" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => 1])) }}" style="border-radius: 30px; color: #8B2452;">1</a>
                </li>
                @if($startPage > 2)
                    <li class="page-item disabled"><span class="page-link rounded-5">...</span></li>
                @endif
            @endif

            @for($i = $startPage; $i <= $endPage; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link rounded-5" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $i])) }}" 
                       style="border-radius: 30px; {{ $i == $currentPage ? 'background: #8B2452; color: white; border-color: #8B2452;' : 'color: #8B2452;' }}">
                        {{ $i }}
                    </a>
                </li>
            @endfor

            @if($endPage < $totalPages)
                @if($endPage < $totalPages - 1)
                    <li class="page-item disabled"><span class="page-link rounded-5">...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link rounded-5" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $totalPages])) }}" style="border-radius: 30px; color: #8B2452;">{{ $totalPages }}</a>
                </li>
            @endif

            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link rounded-5" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $currentPage + 1])) }}" style="border-radius: 30px; color: #8B2452;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

</div>
@endsection