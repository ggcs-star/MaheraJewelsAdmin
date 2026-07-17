@extends('layouts.admin')

@section('content')
@php
    use App\Helpers\S3Helper;
    
    $meta = $order->payment->payment_meta ?? null;
    $subtotal = $meta['subtotal'] ?? $order->subtotal;
    $discount = $meta['discount'] ?? $order->discount;
    $tax = $meta['tax'] ?? $order->tax;
    $shipping = $meta['shipping'] ?? $order->shipping;
    $platformFee = $meta['platform_fee'] ?? $order->platform_fee;
    $total = $meta['total'] ?? $order->total;
    
    $platform = $order->platform ?? 'website';
    $platformLabel = $platform == 'website' ? 'Our Website' : ($platform == 'amazon' ? 'Amazon' : ($platform == 'flipkart' ? 'Flipkart' : 'Offline'));
    
    $statusColor = $order->status == 'pending' ? '#d97706' : ($order->status == 'confirmed' ? '#4338ca' : ($order->status == 'processing' ? '#1d4ed8' : ($order->status == 'shipped' ? '#7c3aed' : ($order->status == 'delivered' ? '#065f46' : '#dc2626'))));
    $statusBg = $order->status == 'pending' ? '#fef3c7' : ($order->status == 'confirmed' ? '#e0e7ff' : ($order->status == 'processing' ? '#dbeafe' : ($order->status == 'shipped' ? '#ede9fe' : ($order->status == 'delivered' ? '#d1fae5' : '#fee2e2'))));
    
    $paymentStatus = $order->payment_status ?? 'pending';
    $paymentLabel = $paymentStatus == 'paid' ? 'Paid' : 'Pending';
    
    $itemsCount = $order->items->count() ?? 0;
    
    $statuses = ['Order Placed', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
    $currentStatusIndex = array_search(ucfirst($order->status ?? 'pending'), $statuses);
    if ($currentStatusIndex === false) $currentStatusIndex = 0;
    
    $timeline = [
        ['status' => 'Order Placed', 'date' => $order->created_at, 'icon' => 'fa-shopping-cart'],
        ['status' => 'Confirmed', 'date' => $order->confirmed_at ?? null, 'icon' => 'fa-check-circle'],
        ['status' => 'Processing', 'date' => $order->processing_at ?? null, 'icon' => 'fa-cogs'],
        ['status' => 'Shipped', 'date' => $order->shipped_at ?? null, 'icon' => 'fa-truck'],
        ['status' => 'Delivered', 'date' => $order->delivered_at ?? null, 'icon' => 'fa-home'],
    ];
@endphp

<style>
    .status-badge {
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Timeline Step Styles */
    .timeline-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        padding: 10px 0;
    }
    .timeline-steps::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #e2ecf5;
        z-index: 0;
    }
    .timeline-steps .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
        text-align: center;
    }
    .timeline-steps .step .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        background: #e2ecf5;
        color: #7c9eb2;
        border: 3px solid #e2ecf5;
        transition: all 0.3s;
    }
    .timeline-steps .step.active .step-circle {
        background: #8B2452;
        color: white;
        border-color: #8B2452;
        box-shadow: 0 0 0 4px rgba(139,36,82,0.2);
    }
    .timeline-steps .step.completed .step-circle {
        background: #8B2452;
        color: white;
        border-color: #8B2452;
    }
    .timeline-steps .step .step-label {
        font-size: 0.6rem;
        font-weight: 600;
        color: #7c9eb2;
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .timeline-steps .step.active .step-label {
        color: #8B2452;
    }
    .timeline-steps .step.completed .step-label {
        color: #0a1e2f;
    }
    .timeline-steps .step .step-date {
        font-size: 0.55rem;
        color: #7c9eb2;
        margin-top: 2px;
    }
    .timeline-steps .step.active .step-date {
        color: #8B2452;
    }
    .timeline-steps .step .step-arrow {
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        color: #8B2452;
        font-size: 1rem;
        display: none;
    }
    .timeline-steps .step:not(:last-child) .step-arrow {
        display: block;
    }
    .timeline-steps .step.completed .step-arrow {
        color: #8B2452;
    }
    .timeline-steps .step.active .step-arrow {
        color: #8B2452;
    }
    .timeline-steps .step.inactive .step-arrow {
        color: #e2ecf5;
    }
    
    .section-divider {
        border: none;
        border-top: 1.5px solid #f0f6fa;
        margin: 16px 0;
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
</style>

<div class="container-fluid px-4 py-4" style="background: #f4f7fc; min-height: 100vh;">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="hover:text-[#8B2452] transition-colors text-decoration-none">Orders</a>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: #7c9eb2;"></i>
                <span class="text-gray-600">Order Details</span>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0a1e2f; margin: 0;">
                Order Details
            </h1>
            <p style="font-size: 0.85rem; color: #7c9eb2; margin: 2px 0 0;">
                Order ID: #{{ $order->order_number ?? 'N/A' }}
                <span class="status-badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; margin-left: 10px;">
                    <i class="fas {{ $order->status == 'pending' ? 'fa-clock' : ($order->status == 'delivered' ? 'fa-check' : 'fa-circle') }} me-1"></i>
                    {{ ucfirst($order->status ?? 'N/A') }}
                </span>
                <span style="font-size: 0.75rem; color: #7c9eb2; margin-left: 10px;">
                    Placed on {{ optional($order->created_at)->format('d M Y, h:i A') }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-5 px-4 py-2" style="font-size:0.7rem;font-weight:600;border-color:#e2ecf5;">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm rounded-5 px-4 py-2" style="background:#dc2626;color:white;font-size:0.7rem;font-weight:600;border:none;">
                <i class="fas fa-file-invoice me-1"></i> Invoice
            </a>
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
                <div class="value">{{ optional($order->user)->name ?? 'Guest' }}</div>
                <div class="value-sm" style="color:#7c9eb2;font-size:0.8rem;">{{ optional($order->shippingAddress)->phone ?? 'N/A' }}</div>
                <div class="value-sm" style="color:#7c9eb2;font-size:0.8rem;">{{ optional($order->shippingAddress)->email ?? optional($order->user)->email ?? 'N/A' }}</div>
                <div class="value-sm" style="color:#7c9eb2;font-size:0.75rem;margin-top:4px;line-height:1.4;">
                    {{ optional($order->shippingAddress)->address_line_1 ?? 'N/A' }}<br>
                    {{ optional($order->shippingAddress)->city }}, {{ optional($order->shippingAddress)->state }} - {{ optional($order->shippingAddress)->postal_code ?? '' }}
                </div>
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
                    <span style="color:#7c9eb2;">Items</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $itemsCount }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Subtotal</span>
                    <span style="font-weight:600;color:#0a1e2f;">₹{{ number_format($subtotal, 2) }}</span>
                </div>
                        @if($discount > 0)
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#0f7b4b;">Discount</span>
                    <span style="font-weight:600;color:#0f7b4b;">-₹{{ number_format($discount, 2) }}</span>
                </div>
                @endif

                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Platform Fee</span>
                    <span style="font-weight:600;color:#0a1e2f;">₹{{ number_format($platformFee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Shipping</span>
                    <span style="font-weight:600;color:#0a1e2f;">₹{{ number_format($shipping, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Tax</span>
                    <span style="font-weight:600;color:#0a1e2f;">₹{{ number_format($tax, 2) }}</span>
                </div>
                <hr class="section-divider" style="margin:8px 0;">
                <div class="d-flex justify-content-between" style="font-size:1rem;font-weight:700;">
                    <span style="color:#8B2452;">Total Amount</span>
                    <span style="color:#8B2452;">₹{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- PAYMENT DETAILS --}}
        <div class="col-md-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-credit-card" style="color:#8B2452;font-size:1rem;"></i>
                    <span class="label">Payment Details</span>
                </div>
                <div style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Method:</span>
                    <span style="font-weight:600;color:#0a1e2f;margin-left:4px;">{{ $order->payment_method ?? 'N/A' }}</span>
                </div>
                <div style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Status:</span>
                    <span class="status-badge" style="background:{{ $paymentStatus == 'paid' ? '#d1fae5' : '#fef3c7' }};color:{{ $paymentStatus == 'paid' ? '#065f46' : '#d97706' }};font-size:0.65rem;padding:2px 12px;margin-left:4px;">
                        <i class="fas {{ $paymentStatus == 'paid' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                        {{ $paymentLabel }}
                    </span>
                </div>
                <div style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">TXN ID:</span>
                    <span style="font-weight:600;color:#0a1e2f;font-family:monospace;font-size:0.75rem;margin-left:4px;">{{ $order->payment->transaction_id ?? 'N/A' }}</span>
                </div>
                <div style="font-size:0.85rem;padding:2px 0;">
                    <span style="color:#7c9eb2;">Paid On:</span>
                    <span style="font-weight:600;color:#0a1e2f;margin-left:4px;">{{ optional($order->payment->created_at ?? $order->created_at)->format('d M Y, h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 2 COLUMN LAYOUT ===== --}}
    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- ORDER TIMELINE - STEPS WITH ARROWS --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom:2px solid #f0f6fa;">
                    <h6 class="mb-0 fw-bold" style="color:#0a1e2f;font-size:0.95rem;">
                        <i class="fas fa-clock me-2" style="color:#8B2452;"></i> Order Timeline
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-steps">
                        @foreach($timeline as $index => $item)
                            @php
                                $isActive = $item['date'] !== null;
                                $isCurrent = $index == $currentStatusIndex;
                                $isCompleted = $index < $currentStatusIndex;
                                $stepClass = $isCompleted ? 'completed' : ($isCurrent ? 'active' : 'inactive');
                                $dateDisplay = $isActive ? optional($item['date'])->format('d M Y, h:i A') : 'Pending';
                            @endphp
                            <div class="step {{ $stepClass }}">
                                <div class="step-circle">
                                    @if($isCompleted)
                                        <i class="fas fa-check"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="step-label">{{ $item['status'] }}</div>
                                <div class="step-date">{{ $dateDisplay }}</div>
                                @if(!$loop->last)
                                    <div class="step-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ORDER ITEMS --}}
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
                        <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">VARIANT</th>
                        <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">SKU</th>
                        <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">PRICE</th>
                        <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">QTY</th>
                        <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">DISCOUNT</th>
                        <th style="padding:10px 14px;text-align:right;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        @php
                            $imgUrl = $item->image ?? ($item->variant->image_url ?? ($item->product->image_url ?? null));
                            if ($imgUrl && !str_starts_with($imgUrl, 'http')) {
                                $imgUrl = S3Helper::url($imgUrl);
                            } elseif (!$imgUrl) {
                                $imgUrl = asset('images/no-image.png');
                            }
                            $variantName = $item->variant->variant_value ?? optional($item->variant->value)->value ?? 'Default';
                            
                            // ✅ Discount calculate karo (agar column nahi hai toh subtotal se)
                            $itemDiscount = $item->discount ?? 0;
                            if ($itemDiscount == 0 && $item->price > 0 && $item->quantity > 0) {
                                $itemTotal = $item->price * $item->quantity;
                                $itemDiscount = max(0, $itemTotal - $item->subtotal);
                            }
                        @endphp
                        <tr style="border-bottom:1px solid #f0f6fa;">
                            <td style="padding:10px 14px;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $imgUrl }}" style="width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid #e9f0f5;" alt="{{ $item->product_name }}">
                                    <div>
                                        <div style="font-weight:600;color:#0a1e2f;font-size:0.8rem;">{{ $item->product_name ?? 'Product' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:10px 14px;font-size:0.75rem;color:#7c9eb2;">{{ $variantName }}</td>
                            <td style="padding:10px 14px;font-size:0.7rem;color:#7c9eb2;font-family:monospace;">{{ $item->sku ?? 'N/A' }}</td>
                            <td style="padding:10px 14px;text-align:center;font-weight:600;color:#0a1e2f;">₹{{ number_format($item->price, 2) }}</td>
                            <td style="padding:10px 14px;text-align:center;font-weight:600;color:#0a1e2f;">{{ $item->quantity }}</td>
                            <td style="padding:10px 14px;text-align:center;font-weight:600;color:#0f7b4b;">
                                @if($itemDiscount > 0)
                                    -₹{{ number_format($itemDiscount, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="padding:10px 14px;text-align:right;font-weight:700;color:#8B2452;">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-box-open" style="font-size:2rem;color:#d4e2f0;"></i>
                                <p class="text-muted mt-2" style="font-size:0.85rem;">No items in this order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot style="background:#f8fcff;border-top:2px solid #e9f0f5;">
                    <tr>
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:700;color:#0a1e2f;">Subtotal:</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:700;color:#0a1e2f;">₹{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    @if($discount > 0)
                    <tr>
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:600;color:#0f7b4b;font-size:0.75rem;">Discount:</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:#0f7b4b;font-size:0.75rem;">-₹{{ number_format($discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">Platform Fee ({{ $platformLabel }}):</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">₹{{ number_format($platformFee, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">Shipping:</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">₹{{ number_format($shipping, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">Tax:</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:#7c9eb2;font-size:0.75rem;">₹{{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr style="background:#f3e8f0;">
                        <td colspan="6" style="padding:10px 14px;text-align:right;font-weight:800;color:#8B2452;font-size:1rem;">Total Amount:</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:800;color:#8B2452;font-size:1rem;">₹{{ number_format($total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom:2px solid #f0f6fa;">
                    <h6 class="mb-0 fw-bold" style="color:#0a1e2f;font-size:0.95rem;">
                        <i class="fas fa-rotate me-2" style="color:#8B2452;"></i> Update Status
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label style="font-size:0.65rem;font-weight:700;color:#7c9eb2;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:4px;">Current Status</label>
                            <span class="status-badge" style="background:{{ $statusBg }};color:{{ $statusColor }};font-size:0.75rem;">
                                <i class="fas {{ $order->status == 'pending' ? 'fa-clock' : ($order->status == 'delivered' ? 'fa-check' : 'fa-circle') }} me-1"></i>
                                {{ ucfirst($order->status ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label style="font-size:0.65rem;font-weight:700;color:#7c9eb2;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:4px;">New Status</label>
                            <select name="status" class="form-select form-select-sm" style="border-radius:30px;border-color:#e2ecf5;font-size:0.8rem;padding:8px 16px;background:#fafcff;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm w-100 rounded-5 py-2" style="background:#8B2452;color:white;font-size:0.75rem;font-weight:600;border:none;">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection