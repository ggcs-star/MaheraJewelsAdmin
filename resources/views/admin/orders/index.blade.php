@extends('layouts.admin')

@section('content')
<style>
    .stat-card {
        transition: all 0.2s ease;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.08);
    }
    .platform-card {
        transition: all 0.2s ease;
        cursor: default;
    }
    .platform-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.06);
    }
    .platform-logo {
        width: 24px;
        height: 24px;
        object-fit: contain;
        margin-right: 8px;
        vertical-align: middle;
    }
    .status-badge {
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-confirmed { background: #e0e7ff; color: #4338ca; }
    .status-processing { background: #dbeafe; color: #1d4ed8; }
    .status-shipped { background: #ede9fe; color: #7c3aed; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #dc2626; }
</style>

<div class="container-fluid px-4 py-4" style="background: #f4f7fc; min-height: 100vh;">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0a1e2f; margin: 0;">
                <i class="fas fa-shopping-cart me-2" style="color: #8B2452;"></i> Orders Management
            </h1>
            <p style="font-size: 0.85rem; color: #7c9eb2; margin: 2px 0 0;">
                Manage and track all customer orders across platforms
            </p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-5" style="background: #10b981; color: white; font-size: 0.65rem; font-weight: 600;">
                <i class="fas fa-circle text-white me-1" style="font-size: 0.35rem;"></i> Live
            </span>
            <span class="badge bg-white text-dark px-3 py-2 rounded-5 border" style="font-size: 0.65rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                <i class="far fa-clock me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </span>
        </div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #8B2452;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Total Orders</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #0a1e2f;">{{ $stats['total'] ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">All platforms</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #f3e8f0; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shopping-cart" style="color: #8B2452; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #d97706;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Pending</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #d97706;">{{ $stats['pending'] ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Awaiting confirmation</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #fef3c7; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="color: #d97706; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #1d4ed8;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Processing</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #1d4ed8;">{{ $stats['processing'] ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Being processed</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #dbeafe; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-cogs" style="color: #1d4ed8; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #065f46;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Delivered</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #065f46;">{{ $stats['delivered'] ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Completed orders</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #d1fae5; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="color: #065f46; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PLATFORM SUMMARY ===== --}}
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center platform-card" style="background: white;">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="https://img.icons8.com/color/24/000000/domain.png" class="platform-logo" alt="Website">
                    <span style="font-size: 0.6rem; font-weight: 600; color: #1a56db; text-transform: uppercase; letter-spacing: 0.5px;">Our Website</span>
                </div>
                <h4 class="fw-bold mb-0" style="color: #1a56db; font-size: 1.4rem;">{{ $platformStats['website']['orders'] ?? 0 }}</h4>
                <span style="font-size: 0.55rem; color: #7c9eb2;">Orders</span>
                <span style="font-size: 0.6rem; font-weight: 600; color: #0a1e2f;">₹{{ number_format($platformStats['website']['total'] ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center platform-card" style="background: white;">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="https://img.icons8.com/color/24/000000/amazon.png" class="platform-logo" alt="Amazon">
                    <span style="font-size: 0.6rem; font-weight: 600; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.5px;">Amazon</span>
                </div>
                <h4 class="fw-bold mb-0" style="color: #f59e0b; font-size: 1.4rem;">{{ $platformStats['amazon']['orders'] ?? 0 }}</h4>
                <span style="font-size: 0.55rem; color: #7c9eb2;">Orders</span>
                <span style="font-size: 0.6rem; font-weight: 600; color: #0a1e2f;">₹{{ number_format($platformStats['amazon']['total'] ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center platform-card" style="background: white;">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('storage/logo/flipkartlogo.jpg') }}" class="platform-logo" alt="Flipkart" onerror="this.style.display='none'">
                    <span style="font-size: 0.6rem; font-weight: 600; color: #ec489a; text-transform: uppercase; letter-spacing: 0.5px;">Flipkart</span>
                </div>
                <h4 class="fw-bold mb-0" style="color: #ec489a; font-size: 1.4rem;">{{ $platformStats['flipkart']['orders'] ?? 0 }}</h4>
                <span style="font-size: 0.55rem; color: #7c9eb2;">Orders</span>
                <span style="font-size: 0.6rem; font-weight: 600; color: #0a1e2f;">₹{{ number_format($platformStats['flipkart']['total'] ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center platform-card" style="background: white; border: 2px solid #8B2452;">
                <div class="d-flex align-items-center justify-content-center">
                    <span style="font-size: 1.2rem; margin-right: 6px;">📊</span>
                    <span style="font-size: 0.6rem; font-weight: 700; color: #8B2452; text-transform: uppercase; letter-spacing: 0.5px;">Total Revenue</span>
                </div>
                <h4 class="fw-bold mb-0" style="color: #8B2452; font-size: 1.4rem;">₹{{ number_format($platformStats['total_revenue'] ?? 0, 2) }}</h4>
                <span style="font-size: 0.55rem; color: #7c9eb2;">All platforms</span>
            </div>
        </div>
    </div>

    {{-- ===== FILTERS ===== --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4" style="background: white;">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
            <div class="col-lg-3 col-md-4 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Search</label>
                <div class="search-wrapper">
                    <input type="text" name="search" id="searchInput" class="form-control form-control-sm" 
                           placeholder="Search Order ID, Customer, SKU..." 
                           value="{{ request('search') }}" 
                           style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Platform</label>
                <select name="platform" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Platforms</option>
                    <option value="website" {{ request('platform') == 'website' ? 'selected' : '' }}>Our Website</option>
                    <option value="amazon" {{ request('platform') == 'amazon' ? 'selected' : '' }}>Amazon</option>
                    <option value="flipkart" {{ request('platform') == 'flipkart' ? 'selected' : '' }}>Flipkart</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Status</label>
                <select name="status" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Date Range</label>
                <select name="date_range" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-filter w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn-reset">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="card border-0 shadow-sm rounded-4" style="background: white; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom: 2px solid #f0f6fa;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold" style="color: #0a1e2f; font-size: 0.95rem;">
                    <i class="fas fa-list me-2" style="color: #8B2452;"></i> Recent Orders
                </h6>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="font-size: 0.65rem; color: #7c9eb2; cursor: pointer;">Export</span>
                    <span style="font-size: 0.65rem; color: #7c9eb2; cursor: pointer;">Refresh</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem;">
                    <thead style="background: #f8fcff; border-bottom: 2px solid #e9f0f5;">
                        <tr>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 180px;">ORDER DETAILS</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 100px;">PLATFORM</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 140px;">CUSTOMER</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 120px;">DATE</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 100px;">STATUS</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 80px;">ITEMS</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 100px;">TOTAL</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 100px;">PAYMENT</th>
                            <th style="padding: 12px 14px; font-weight: 700; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: #3e6579; min-width: 80px; text-align: center;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse($orders as $order)
        @php
            $isAmazon = isset($order->amazon_order_id) || ($order->is_amazon ?? false);
            
            $platform = $isAmazon ? 'amazon' : ($order->platform ?? 'website');
            $platformLabel = $platform == 'website' ? 'Our Website' : ($platform == 'amazon' ? 'Amazon' : ($platform == 'flipkart' ? 'Flipkart' : 'Offline'));
            $platformColor = $platform == 'website' ? '#1a56db' : ($platform == 'amazon' ? '#f59e0b' : ($platform == 'flipkart' ? '#ec489a' : '#0f7b4b'));
            
            $orderNumber = $isAmazon ? $order->amazon_order_id : $order->order_number;
            $status = $isAmazon ? $order->order_status : $order->status;
            $statusClass = 'status-' . strtolower($status ?? 'pending');
            $customerName = $isAmazon ? ($order->shipping_name ?? 'Amazon Customer') : (optional($order->user)->name ?? 'Guest');
            $customerPhone = $isAmazon ? 'N/A' : (optional($order->shippingAddress)->phone ?? 'N/A');
            $orderTotal = $isAmazon
    ? ($order->order_total > 0
        ? $order->order_total
        : $order->items->sum(function ($item) {
            return $item->item_price * max(1, $item->quantity_ordered);
        }))
    : $order->total;
            $itemsCount = $order->items->count() ?? 0;
            $paymentStatus = $isAmazon ? 'paid' : ($order->payment_status ?? 'pending');
            $paymentLabel = $paymentStatus == 'paid' ? 'Paid' : 'Pending';
            $paymentColor = $paymentStatus == 'paid' ? '#0f7b4b' : '#d97706';
            $paymentMethod = $isAmazon ? 'Amazon Pay' : ($order->payment_method ?? 'N/A');
            $orderDate = $isAmazon ? $order->purchase_date : $order->created_at;
        @endphp
        <tr style="border-bottom: 1px solid #f0f6fa; transition: background 0.15s ease;">
            <td style="padding: 12px 14px;">
                <div style="font-weight: 700; color: #0a1e2f; font-size: 0.85rem;">{{ $orderNumber }}</div>
                <div style="font-size: 0.6rem; color: #7c9eb2;">
                    #{{ $order->id ?? 'N/A' }}
                </div>
            </td>
            <td style="padding: 12px 14px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    @if($platform == 'website')
                        <img src="https://img.icons8.com/color/20/000000/domain.png" style="width:18px;height:18px;vertical-align:middle;">
                    @elseif($platform == 'amazon')
                        <img src="https://img.icons8.com/color/20/000000/amazon.png" style="width:18px;height:18px;vertical-align:middle;">
                    @elseif($platform == 'flipkart')
                        <img src="{{ asset('storage/logo/flipkartlogo.jpg') }}" style="width:18px;height:18px;vertical-align:middle;border-radius:4px;" onerror="this.style.display='none'">
                    @else
                        <span style="font-size:16px;">🛒</span>
                    @endif
                    <span style="font-size: 0.7rem; font-weight: 600; color: {{ $platformColor }};">{{ $platformLabel }}</span>
                </div>
                <div style="font-size: 0.55rem; color: #7c9eb2;">
                    {{ $platform == 'website' ? 'Online Store' : ($platform == 'amazon' ? 'Amazon.in' : ($platform == 'flipkart' ? 'Flipkart' : 'Walk-in Store')) }}
                </div>
            </td>
            <td style="padding: 12px 14px;">
                <div style="font-weight: 500; color: #0a1e2f; font-size: 0.8rem;">{{ $customerName }}</div>
                <div style="font-size: 0.6rem; color: #7c9eb2;">{{ $customerPhone }}</div>
            </td>
            <td style="padding: 12px 14px;">
                <div style="font-size: 0.7rem; color: #0a1e2f;">{{ optional($orderDate)->format('d M Y') }}</div>
                <div style="font-size: 0.6rem; color: #7c9eb2;">{{ optional($orderDate)->format('h:i A') }}</div>
            </td>
            <td style="padding: 12px 14px;">
                <span class="status-badge {{ $statusClass }}">{{ ucfirst($status ?? 'N/A') }}</span>
            </td>
            <td style="padding: 12px 14px; text-align: center;">
                <div style="font-weight: 600; color: #0a1e2f; font-size: 0.85rem;">{{ $itemsCount }}</div>
                <div style="font-size: 0.55rem; color: #7c9eb2;">{{ $itemsCount == 1 ? 'Item' : 'Items' }}</div>
            </td>
            <td style="padding: 12px 14px;">
                <div style="font-weight: 700; color: #0a1e2f; font-size: 0.9rem;">₹{{ number_format($orderTotal ?? 0, 2) }}</div>
            </td>
            <td style="padding: 12px 14px;">
                <div style="font-weight: 600; color: {{ $paymentColor }}; font-size: 0.75rem;">{{ $paymentLabel }}</div>
                <div style="font-size: 0.55rem; color: #7c9eb2;">{{ $paymentMethod }}</div>
            </td>
            <td style="padding: 12px 14px; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                    @if($isAmazon)
                        <a href="{{ route('admin.orders.show', $order->id) }}" style="background: transparent; border: none; color: #f59e0b; font-size: 0.9rem;" title="View Amazon Order">
                            <i class="fas fa-eye"></i>
                        </a>
                        <span style="font-size: 0.55rem; color: #7c9eb2; font-style: italic;">Read-only</span>
                    @else
                        <a href="{{ route('admin.orders.show', [
                            'id' => $order->id,
                            'back' => url()->full()
                        ]) }}"
                        style="background: transparent; border: none; color: #f59e0b; font-size: 0.9rem;"
                        title="View Amazon Order">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" style="background: transparent; border: none; color: #7c9eb2; font-size: 0.9rem;" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#7c9eb2'" title="Invoice">
                            <i class="fas fa-file-invoice"></i>
                        </a>
                        <a href="{{ route('admin.orders.show', $order->id) }}" style="background: transparent; border: none; color: #7c9eb2; font-size: 0.9rem;" onmouseover="this.style.color='#0f7b4b'" onmouseout="this.style.color='#7c9eb2'" title="Manage">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center py-5">
                <i class="fas fa-box-open" style="font-size: 2.5rem; color: #d4e2f0;"></i>
                <p class="text-muted mt-2" style="font-size: 0.9rem;">No orders found</p>
                <p style="font-size: 0.75rem; color: #7c9eb2;">Try adjusting your search or filter</p>
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($orders->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
            <div style="font-size: 0.78rem; color: #7c9eb2;">
                <i class="fas fa-info-circle me-1"></i> 
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
            </div>
            <nav>
                {{ $orders->links() }}
            </nav>
        </div>
    @endif
</div>

<style>
    .btn-filter {
        background: #8B2452;
        border: none;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s ease;
    }
    .btn-filter:hover {
        background: #6B1A3A;
    }
    .btn-reset {
        background: transparent;
        border: 1px solid #e2ecf5;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #3e6579;
        transition: all 0.2s ease;
    }
    .btn-reset:hover {
        background: #f0f6fa;
        border-color: #8B2452;
        color: #8B2452;
    }
    .pagination .page-link {
        border-radius: 30px !important;
        color: #3e6579;
        border: 1px solid #e2ecf5;
        padding: 6px 14px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.2s ease;
    }
    .pagination .page-link:hover {
        background: #f0f6fa;
        border-color: #8B2452;
        color: #8B2452;
    }
    .pagination .page-item.active .page-link {
        background: #8B2452;
        border-color: #8B2452;
        color: white;
    }
</style>
@endsection