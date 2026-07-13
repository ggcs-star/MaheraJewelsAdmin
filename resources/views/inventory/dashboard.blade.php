@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4" style="background: #f8fafc; min-height: 100vh;">

    {{-- ========== HEADER ========== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold" style="font-size: 1.6rem; color: #0a1e2f;">📦 Master Inventory Dashboard</h1>
            <p class="text-muted" style="font-size: 0.85rem;">Consolidated master inventory across all channels</p>
        </div>
        <div>
            <span class="badge bg-success px-3 py-2 rounded-5 me-2" style="font-size: 0.7rem;">
                <i class="fas fa-circle text-white me-1" style="font-size: 0.4rem;"></i> Live
            </span>
            <span class="badge bg-light text-dark px-3 py-2 rounded-5 border" style="font-size: 0.7rem;">
                <i class="far fa-clock me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </span>
        </div>
    </div>

    {{-- ========== FILTER TABS ========== --}}
    <div class="mb-4">
        <div class="d-flex gap-2 flex-wrap" style="background: white; padding: 6px; border-radius: 60px; border: 1px solid #e9f0f5; width: fit-content;">
            <a href="?filter=all" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter', 'all') == 'all' ? 'bg-[#8B2452] text-white' : 'text-gray-600' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.8rem;">
                📊 All
            </a>
            <a href="?filter=website" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'website' ? 'bg-[#8B2452] text-white' : 'text-gray-600' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.8rem;">
                🌐 Website
            </a>
            <a href="?filter=offline" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'offline' ? 'bg-[#8B2452] text-white' : 'text-gray-600' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.8rem;">
                🏪 Offline
            </a>
            <a href="?filter=low" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'low' ? 'bg-[#8B2452] text-white' : 'text-gray-600' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.8rem;">
                ⚠️ Low Stock
            </a>
            <a href="?filter=out" 
               class="px-4 py-2 rounded-5 text-decoration-none {{ request('filter') == 'out' ? 'bg-[#8B2452] text-white' : 'text-gray-600' }}"
               style="transition: all 0.2s; font-weight: 600; font-size: 0.8rem;">
                ❌ Out of Stock
            </a>
        </div>
    </div>

    {{-- ========== STATS CARDS ========== --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: white;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: #eef7ff; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-boxes" style="color: #8B2452; font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Products</span>
                        <h3 class="fw-bold mb-0" style="color: #0a1e2f;">{{ $summary['total_products'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">units</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: white;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: #e8f3ed; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-warehouse" style="color: #0f7b4b; font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Stock</span>
                        <h3 class="fw-bold mb-0" style="color: #0a1e2f;">{{ $summary['total_stock'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">units</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: white;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: #e8f0fe; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-globe" style="color: #1a56db; font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Website Stock</span>
                        <h3 class="fw-bold mb-0" style="color: #1a56db;">{{ $summary['total_website'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">units</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100" style="background: white;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: #e8f3ed; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store" style="color: #0f7b4b; font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Offline Stock</span>
                        <h3 class="fw-bold mb-0" style="color: #0f7b4b;">{{ $summary['total_offline'] }}</h3>
                        <span style="font-size: 0.7rem; color: #7c9eb2;">units</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== CHANNEL REVENUE & STATUS ========== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3" style="background: white;">
                <h6 class="fw-bold mb-3" style="color: #0a1e2f;">
                    <i class="fas fa-chart-bar me-2" style="color: #8B2452;"></i> Channel Revenue Breakdown
                </h6>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span style="font-size: 0.85rem;">🛒 Offline Sale</span>
                    <span class="fw-bold" style="color: #0f7b4b;">₹{{ number_format($summary['total_offline_sold'] * 2000, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span style="font-size: 0.85rem;">🌐 Website Store</span>
                    <span class="fw-bold" style="color: #1a56db;">₹{{ number_format($summary['total_website_sold'] * 2000, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="fw-bold" style="font-size: 0.9rem;">📊 Total Revenue</span>
                    <span class="fw-bold" style="color: #8B2452; font-size: 1.2rem;">₹{{ number_format(($summary['total_website_sold'] + $summary['total_offline_sold']) * 2000, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3" style="background: white;">
                <h6 class="fw-bold mb-3" style="color: #0a1e2f;">
                    <i class="fas fa-sync me-2" style="color: #8B2452;"></i> Channel Status
                </h6>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span style="font-size: 0.85rem;">🏪 Offline Sale</span>
                    <span class="badge bg-success rounded-5 px-3 py-2">✅ Realtime POS</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span style="font-size: 0.85rem;">🌐 Website Store</span>
                    <span class="badge bg-success rounded-5 px-3 py-2">✅ Realtime Webhook</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 0.85rem;">📦 Amazon India</span>
                    <span class="badge bg-warning text-dark rounded-5 px-3 py-2">⏳ 5 mins scheduler</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== INVENTORY TABLE ========== --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white rounded-4 rounded-bottom-0 p-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color: #0a1e2f;">
                    <i class="fas fa-list me-2" style="color: #8B2452;"></i> Inventory Allocation Catalog
                </h6>
                <span class="badge bg-light text-dark px-3 py-2 rounded-5 border">{{ count($inventoryData) }} items</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead style="background: #f8fcff; border-bottom: 2px solid #e9f0f5;">
                        <tr>
                            <th class="px-3 py-3" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Image</th>
                            <th class="px-3 py-3" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #1a56db; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Website</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #0f7b4b; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Offline</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #dc3545; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Sold</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #0a1e2f; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Available</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th class="px-3 py-3 text-center" style="font-weight: 700; color: #3e6579; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $filter = request('filter', 'all'); @endphp
                        @foreach($inventoryData as $item)
                            @php
                                $show = true;
                                if ($filter == 'website' && $item['website_available'] <= 0) $show = false;
                                if ($filter == 'offline' && $item['offline_available'] <= 0) $show = false;
                                if ($filter == 'low' && $item['final_stock'] > 5) $show = false;
                                if ($filter == 'out' && $item['final_stock'] > 0) $show = false;

                                // ✅ Filter wise sold display
                                $soldDisplay = '';
                                if ($filter == 'website' || $filter == 'all') {
                                    $soldDisplay = $item['website_sold'] . ' (Website)';
                                }
                                if ($filter == 'offline' || $filter == 'all') {
                                    if ($filter == 'all') {
                                        $soldDisplay = $item['website_sold'] . ' (Website) + ' . $item['offline_sold'] . ' (Offline)';
                                    } else {
                                        $soldDisplay = $item['offline_sold'] . ' (Offline)';
                                    }
                                }

                                // ✅ Filter wise available display
                                $availableDisplay = '';
                                if ($filter == 'website' || $filter == 'all') {
                                    $availableDisplay = $item['website_available'];
                                }
                                if ($filter == 'offline') {
                                    $availableDisplay = $item['offline_available'];
                                }
                            @endphp
                            @if($show)
                            <tr style="border-bottom: 1px solid #f0f6fa;">
                                <td class="px-3 py-3">
                                    @if($item['image_url'])
                                        <img src="{{ \App\Helpers\S3Helper::url($item['image_url']) }}" 
                                             style="width: 45px; height: 45px; object-fit: cover; border-radius: 10px; border: 1px solid #e9f0f5;">
                                    @else
                                        <div style="width: 45px; height: 45px; background: #f1f7fd; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #e9f0f5;">
                                            <i class="fas fa-box" style="color: #7c9eb2;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    <div class="fw-semibold" style="color: #0a1e2f; font-size: 0.85rem;">{{ $item['product_name'] }}</div>
                                    <div style="font-size: 0.7rem; color: #7c9eb2;">{{ $item['variant_name'] }}</div>
                                    <code style="font-size: 0.65rem; color: #7c9eb2;">{{ $item['sku'] }}</code>
                                </td>
                                <td class="px-3 py-3 text-center fw-bold">{{ $item['total_stock'] }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if($filter == 'offline')
                                        <span class="text-muted">-</span>
                                    @else
                                        <span class="fw-bold" style="color: #1a56db; font-size: 0.9rem;">{{ $item['website_available'] }}</span>
                                        <span style="font-size: 0.6rem; color: #7c9eb2;">/ {{ $item['website_pushed'] }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if($filter == 'website')
                                        <span class="text-muted">-</span>
                                    @else
                                        <span class="fw-bold" style="color: #0f7b4b; font-size: 0.9rem;">{{ $item['offline_available'] }}</span>
                                        <span style="font-size: 0.6rem; color: #7c9eb2;">/ {{ $item['offline_pushed'] }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="fw-bold text-danger">{{ $item['total_sold'] }}</span>
                                    <div style="font-size: 0.6rem; color: #7c9eb2;">
                                        @if($filter == 'website')
                                            {{ $item['website_sold'] }} Website
                                        @elseif($filter == 'offline')
                                            {{ $item['offline_sold'] }} Offline
                                        @else
                                            {{ $item['website_sold'] }} Website + {{ $item['offline_sold'] }} Offline
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center fw-bold" style="color: #0a1e2f; font-size: 0.9rem;">
                                    @if($filter == 'website')
                                        {{ $item['website_available'] }}
                                    @elseif($filter == 'offline')
                                        {{ $item['offline_available'] }}
                                    @else
                                        {{ $item['final_stock'] }}
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if($item['final_stock'] > 10)
                                        <span class="badge bg-success px-3 py-2 rounded-5" style="font-size: 0.7rem;">✅ In Stock</span>
                                    @elseif($item['final_stock'] > 0)
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-5" style="font-size: 0.7rem;">⚠️ Low Stock</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2 rounded-5" style="font-size: 0.7rem;">❌ Out of Stock</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary rounded-5 px-3" style="font-size: 0.7rem; font-weight: 600;">
                                        Manage <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection