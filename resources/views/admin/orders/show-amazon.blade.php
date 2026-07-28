@extends('layouts.admin')

@section('content')
<style>
    .status-badge {
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #f0f6fa;
        height: 100%;
    }
    .info-card .label {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7c9eb2;
        margin-bottom: 4px;
    }
    .info-card .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0a1e2f;
    }
    .info-card .value-sm {
        font-size: 0.8rem;
        font-weight: 500;
        color: #0a1e2f;
    }
    .section-divider {
        border: none;
        border-top: 1.5px solid #f0f6fa;
        margin: 16px 0;
    }
</style>

<div class="container-fluid px-4 py-4" style="background: #f4f7fc; min-height: 100vh;">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="hover:text-[#8B2452] transition-colors text-decoration-none">Orders</a>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: #7c9eb2;"></i>
                <span class="text-gray-600">Amazon Order Details</span>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0a1e2f; margin: 0;">
                <i class="fab fa-amazon me-2" style="color: #8B2452;"></i> Amazon Order
            </h1>
            <p style="font-size: 0.85rem; color: #7c9eb2; margin: 2px 0 0;">
                Order ID: <strong style="color: #0a1e2f;">{{ $amazonOrder->amazon_order_id }}</strong>
                @php
                    $statusColor = $amazonOrder->order_status == 'Pending' ? '#d97706' : ($amazonOrder->order_status == 'Shipped' ? '#7c3aed' : ($amazonOrder->order_status == 'Delivered' ? '#065f46' : '#4338ca'));
                    $statusBg = $amazonOrder->order_status == 'Pending' ? '#fef3c7' : ($amazonOrder->order_status == 'Shipped' ? '#ede9fe' : ($amazonOrder->order_status == 'Delivered' ? '#d1fae5' : '#e0e7ff'));
                @endphp
                <span class="status-badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; margin-left: 10px;">
                    <i class="fas {{ $amazonOrder->order_status == 'Pending' ? 'fa-clock' : ($amazonOrder->order_status == 'Delivered' ? 'fa-check' : 'fa-circle') }} me-1"></i>
                    {{ $amazonOrder->order_status ?? 'N/A' }}
                </span>
                <span style="font-size: 0.75rem; color: #7c9eb2; margin-left: 10px;">
                    Purchased on {{ optional($amazonOrder->purchase_date)->format('d M Y, h:i A') }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index', ['source' => 'amazon']) }}" class="btn btn-sm btn-outline-secondary rounded-5 px-4 py-2" style="font-size:0.7rem;font-weight:600;border-color:#e2ecf5;">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <span class="btn btn-sm rounded-5 px-4 py-2" style="background:#8B2452;color:white;font-size:0.7rem;font-weight:600;border:none;cursor:default;">
                <i class="fab fa-amazon me-1"></i> Amazon
            </span>
        </div>
    </div>

    {{-- ===== 3 CARDS ROW ===== --}}
    <div class="row g-3 mb-4">
        {{-- CUSTOMER DETAILS --}}
        <div class="col-md-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-user" style="color:#8B2452;font-size:1rem;"></i>
                    <span class="label">Customer Details</span>
                </div>
                <div class="value">{{ $amazonOrder->shipping_name ?? 'Amazon Customer' }}</div>
                <div class="value-sm" style="color:#7c9eb2;font-size:0.8rem;">{{ $amazonOrder->shipping_city ?? '' }}, {{ $amazonOrder->shipping_state ?? '' }}</div>
                <div class="value-sm" style="color:#7c9eb2;font-size:0.8rem;">{{ $amazonOrder->shipping_postal_code ?? '' }}, {{ $amazonOrder->shipping_country ?? '' }}</div>
            </div>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="col-md-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-chart-simple" style="color:#8B2452;font-size:1rem;"></i>
                    <span class="label">Order Summary</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Sales Channel</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->sales_channel ?? 'Amazon' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Fulfillment</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->fulfillment_channel ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Payment Method</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->payment_method ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Currency</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->currency ?? 'INR' }}</span>
                </div>
                <hr class="section-divider" style="margin:8px 0;">
                <div class="d-flex justify-content-between" style="font-size:1rem;font-weight:700;">
                    <span style="color:#8B2452;">Total Amount</span>
                    <span style="color:#8B2452;">₹{{ number_format($amazonOrder->order_total ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- SHIPPING DETAILS --}}
        <div class="col-md-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-truck" style="color:#8B2452;font-size:1rem;"></i>
                    <span class="label">Shipping Details</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Items Shipped</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->number_of_items_shipped ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Items Unshipped</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->number_of_items_unshipped ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Shipment Level</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $amazonOrder->shipment_service_level_category ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Earliest Ship</span>
                    <span style="font-weight:600;color:#0a1e2f;font-size:0.75rem;">{{ optional($amazonOrder->earliest_ship_date)->format('d M Y') ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Latest Ship</span>
                    <span style="font-weight:600;color:#0a1e2f;font-size:0.75rem;">{{ optional($amazonOrder->latest_ship_date)->format('d M Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ORDER ITEMS ===== --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom:2px solid #f0f6fa;">
            <h6 class="mb-0 fw-bold" style="color:#0a1e2f;font-size:0.95rem;">
                <i class="fas fa-box me-2" style="color:#8B2452;"></i> Order Items
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.8rem;">
                    <thead style="background:#f8fcff;border-bottom:2px solid #e9f0f5;">
                        <tr>
                            <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">PRODUCT</th>
                            <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">SKU</th>
                            <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">ASIN</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">QTY</th>
                            <th style="padding:10px 14px;text-align:right;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">PRICE</th>
                            <th style="padding:10px 14px;text-align:right;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($amazonOrder->items as $item)
                            <tr style="border-bottom:1px solid #f0f6fa;">
                                <td style="padding:10px 14px;">
                                    <div style="font-weight:600;color:#0a1e2f;font-size:0.8rem;">{{ $item->title ?? 'Product' }}</div>
                                    <div style="font-size:0.65rem;color:#7c9eb2;">{{ $item->amazon_order_item_id ?? 'N/A' }}</div>
                                </td>
                                <td style="padding:10px 14px;font-size:0.7rem;color:#7c9eb2;font-family:monospace;">{{ $item->seller_sku ?? 'N/A' }}</td>
                                <td style="padding:10px 14px;font-size:0.7rem;color:#7c9eb2;font-family:monospace;">{{ $item->asin ?? 'N/A' }}</td>
                                <td style="padding:10px 14px;text-align:center;font-weight:600;color:#0a1e2f;">
                                    {{ $item->quantity_ordered ?? 0 }}
                                    @if(($item->quantity_shipped ?? 0) > 0)
                                        <span style="font-size:0.55rem;color:#0f7b4b;display:block;">Shipped: {{ $item->quantity_shipped }}</span>
                                    @endif
                                </td>
                                <td style="padding:10px 14px;text-align:right;font-weight:600;color:#0a1e2f;">₹{{ number_format($item->item_price ?? 0, 2) }}</td>
                                <td style="padding:10px 14px;text-align:right;font-weight:700;color:#8B2452;">
                                    ₹{{ number_format(($item->item_price ?? 0) * ($item->quantity_ordered ?? 0), 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open" style="font-size:2rem;color:#d4e2f0;"></i>
                                    <p class="text-muted mt-2" style="font-size:0.85rem;">No items in this order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background:#f8fcff;border-top:2px solid #e9f0f5;">
                        <tr>
                            <td colspan="5" style="padding:10px 14px;text-align:right;font-weight:700;color:#0a1e2f;">Total:</td>
                            <td style="padding:10px 14px;text-align:right;font-weight:800;color:#8B2452;font-size:1.1rem;">
                                ₹{{ number_format($amazonOrder->order_total ?? 0, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== NOTE ===== --}}
    <div class="mt-4">
        <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:16px;border-color:#8B2452;background:#f3e8f0;" role="alert">
            <i class="fas fa-info-circle" style="color:#8B2452;"></i>
            <div style="font-size:0.8rem;color:#7c9eb2;">
                <strong>Note:</strong> This is an Amazon order. Status and details cannot be modified from here.
                Amazon orders are synced automatically from Amazon SP-API.
            </div>
        </div>
    </div>
</div>
@endsection