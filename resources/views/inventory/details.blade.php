@extends('layouts.admin')

@section('title', 'Inventory Details')

@section('content')
<style>
    .status-badge {
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #f0f6fa;
        transition: all 0.2s ease;
        height: 100%;
    }
    .stat-card:hover {
        border-color: #8B2452;
        box-shadow: 0 4px 12px rgba(139,36,82,0.08);
    }
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0a1e2f;
        line-height: 1.2;
    }
    .stat-card .stat-label {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7c9eb2;
    }
    .platform-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #f0f6fa;
        transition: all 0.2s ease;
        height: 100%;
    }
    .platform-card:hover {
        border-color: #8B2452;
        box-shadow: 0 4px 12px rgba(139,36,82,0.08);
    }
    .platform-card .platform-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
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
                <a href="{{ route('admin.inventory.dashboard') }}" class="hover:text-[#8B2452] transition-colors text-decoration-none">Inventory</a>
                <i class="fas fa-chevron-right" style="font-size: 0.5rem; color: #7c9eb2;"></i>
                <span class="text-gray-600">Product Details</span>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0a1e2f; margin: 0;">
                <i class="fas fa-boxes me-2" style="color: #8B2452;"></i> Inventory Details
            </h1>
            <p style="font-size: 0.85rem; color: #7c9eb2; margin: 2px 0 0;">
                {{ $product->name }}
                @if($product->category)
                    <span style="color:#7c9eb2;font-size:0.75rem;">| {{ $product->category->name }}</span>
                @endif
                <span class="status-badge" style="background: {{ $product->status ? '#d1fae5' : '#fee2e2' }}; color: {{ $product->status ? '#065f46' : '#dc2626' }}; margin-left: 10px;">
                    <i class="fas {{ $product->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                    {{ $product->status ? 'Active' : 'Inactive' }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.inventory.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-5 px-4 py-2" style="font-size:0.7rem;font-weight:600;border-color:#e2ecf5;">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <a href="#" class="btn btn-sm rounded-5 px-4 py-2" style="background:#8B2452;color:white;font-size:0.7rem;font-weight:600;border:none;">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    {{-- ===== PRODUCT INFO CARD ===== --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom:2px solid #f0f6fa;">
            <h6 class="mb-0 fw-bold" style="color:#0a1e2f;font-size:0.95rem;">
                <i class="fas fa-info-circle me-2" style="color:#8B2452;"></i> Product Information
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-2">
                    @php
                        $image = $product->gallery_images_public[0] ?? null;
                    @endphp
                    @if($image)
                        <img src="{{ $image }}" alt="{{ $product->name }}"
                             class="img-fluid rounded-4 border"
                             style="width:170px;height:170px;object-fit:cover;">
                    @else
                        <div class="border rounded-4 d-flex align-items-center justify-content-center"
                             style="width:170px;height:170px;background:#fafcff;">
                            <i class="fas fa-image fa-3x" style="color:#d4e2f0;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-lg-10">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">Product Name</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;">{{ $product->name }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">SKU</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;font-family:monospace;">{{ $product->sku }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">Brand</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;">{{ $product->brand ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">Supplier</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;">{{ optional($product->supplier)->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">Category</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;">{{ optional($product->category)->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#7c9eb2;">Total Variants</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#0a1e2f;">{{ count($variantData) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 4 STAT CARDS ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#f3e8f0;color:#8B2452;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div>
                    <div class="stat-label">Master Stock</div>
                    <div class="stat-number">{{ number_format($summary['master_stock']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div>
                    <div class="stat-label">Total Pushed</div>
                    <div class="stat-number">{{ number_format($summary['total_pushed']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="stat-label">Total Sold</div>
                    <div class="stat-number">{{ number_format($summary['total_sold']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;color:#065f46;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Available Stock</div>
                    <div class="stat-number">{{ number_format($summary['available_stock']) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PLATFORM SUMMARY ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="platform-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="platform-icon" style="background:#dbeafe;color:#1d4ed8;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h6 class="mb-0 fw-bold" style="color:#0a1e2f;">Website</h6>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f0f6fa;">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Pushed</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $summary['website_push'] }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Sold</span>
                    <span style="font-weight:700;color:#dc2626;">{{ $summary['website_sold'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="platform-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="platform-icon" style="background:#f3e8f0;color:#8B2452;">
                        <i class="fas fa-store"></i>
                    </div>
                    <h6 class="mb-0 fw-bold" style="color:#0a1e2f;">Offline</h6>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f0f6fa;">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Pushed</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $summary['offline_push'] }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Sold</span>
                    <span style="font-weight:700;color:#dc2626;">{{ $summary['offline_sold'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="platform-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="platform-icon" style="background:#fef3c7;color:#d97706;">
                        <i class="fab fa-amazon"></i>
                    </div>
                    <h6 class="mb-0 fw-bold" style="color:#0a1e2f;">Amazon</h6>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f0f6fa;">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Pushed</span>
                    <span style="font-weight:600;color:#0a1e2f;">{{ $summary['amazon_push'] }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2">
                    <span style="color:#7c9eb2;font-size:0.85rem;">Sold</span>
                    <span style="font-weight:700;color:#dc2626;">{{ $summary['amazon_sold'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== VARIANT INVENTORY TABLE ===== --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom:2px solid #f0f6fa;">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:#0a1e2f;font-size:0.95rem;">
                    <i class="fas fa-list me-2" style="color:#8B2452;"></i> Variant Inventory Details
                </h6>
                <span class="badge" style="background:#f3e8f0;color:#8B2452;font-size:0.65rem;padding:6px 14px;border-radius:30px;">
                    <i class="fas fa-layer-group me-1"></i> {{ count($variantData) }} Variants
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.8rem;">
                    <thead style="background:#f8fcff;border-bottom:2px solid #e9f0f5;">
                        <tr>
                            <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">#</th>
                            <th style="padding:10px 14px;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Variant</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Master Stock</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Total Push</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Total Sold</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Available</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Website</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Offline</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.5px;color:#3e6579;">Amazon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalMaster = 0;
                            $totalPush = 0;
                            $totalSold = 0;
                            $totalAvailable = 0;
                        @endphp

                        @if(count($variantData))
                            @foreach($variantData as $index => $row)
                                @php
                                    $totalMaster += $row['master_stock'];
                                    $totalPush += $row['total_push'];
                                    $totalSold += $row['total_sold'];
                                    $totalAvailable += $row['available'];
                                @endphp
                                <tr style="border-bottom:1px solid #f0f6fa;">
                                    <td style="padding:10px 14px;font-weight:600;color:#7c9eb2;">{{ $index + 1 }}</td>
                                    <td style="padding:10px 14px;">
                                        <div style="font-weight:600;color:#0a1e2f;">
                                            {{ optional($row['variant']->variant)->name }}
                                            @if($row['variant']->value)
                                                <span style="color:#7c9eb2;font-size:0.75rem;">- {{ $row['variant']->value->value }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size:0.65rem;color:#7c9eb2;font-family:monospace;">SKU: {{ $row['variant']->sku_suffix }}</div>
                                    </td>
                                    <td style="padding:10px 14px;text-align:center;">
                                        <span class="badge" style="background:#dbeafe;color:#1d4ed8;font-size:0.7rem;padding:4px 12px;border-radius:30px;font-weight:600;">
                                            {{ $row['master_stock'] }}
                                        </span>
                                    </td>
                                    <td style="padding:10px 14px;text-align:center;">
                                        <span class="badge" style="background:#e0e7ff;color:#4338ca;font-size:0.7rem;padding:4px 12px;border-radius:30px;font-weight:600;">
                                            {{ $row['total_push'] }}
                                        </span>
                                    </td>
                                    <td style="padding:10px 14px;text-align:center;">
                                        <span class="badge" style="background:#fee2e2;color:#dc2626;font-size:0.7rem;padding:4px 12px;border-radius:30px;font-weight:600;">
                                            {{ $row['total_sold'] }}
                                        </span>
                                    </td>
                                    <td style="padding:10px 14px;text-align:center;">
                                        @if($row['available'] > 0)
                                            <span class="badge" style="background:#d1fae5;color:#065f46;font-size:0.75rem;padding:4px 14px;border-radius:30px;font-weight:600;">
                                                <i class="fas fa-check-circle me-1"></i> {{ $row['available'] }}
                                            </span>
                                        @else
                                            <span class="badge" style="background:#fee2e2;color:#dc2626;font-size:0.75rem;padding:4px 14px;border-radius:30px;font-weight:600;">
                                                <i class="fas fa-times-circle me-1"></i> 0
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 14px;">
                                        <div style="font-size:0.7rem;text-align:center;">
                                            <div style="color:#0a1e2f;font-weight:600;">Push: {{ $row['website_push'] }}</div>
                                            <div style="color:#dc2626;font-weight:600;">Sold: {{ $row['website_sold'] }}</div>
                                        </div>
                                    </td>
                                    <td style="padding:10px 14px;">
                                        <div style="font-size:0.7rem;text-align:center;">
                                            <div style="color:#0a1e2f;font-weight:600;">Push: {{ $row['offline_push'] }}</div>
                                            <div style="color:#dc2626;font-weight:600;">Sold: {{ $row['offline_sold'] }}</div>
                                        </div>
                                    </td>
                                    <td style="padding:10px 14px;">
                                        <div style="font-size:0.7rem;text-align:center;">
                                            <div style="color:#0a1e2f;font-weight:600;">Push: {{ $row['amazon_push'] }}</div>
                                            <div style="color:#dc2626;font-weight:600;">Sold: {{ $row['amazon_sold'] }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background:#f8fcff;border-top:2px solid #e9f0f5;">
                                <td colspan="2" style="padding:10px 14px;font-weight:700;color:#0a1e2f;">Total</td>
                                <td style="padding:10px 14px;text-align:center;font-weight:700;color:#1d4ed8;">{{ $totalMaster }}</td>
                                <td style="padding:10px 14px;text-align:center;font-weight:700;color:#4338ca;">{{ $totalPush }}</td>
                                <td style="padding:10px 14px;text-align:center;font-weight:700;color:#dc2626;">{{ $totalSold }}</td>
                                <td style="padding:10px 14px;text-align:center;font-weight:700;color:#065f46;">{{ $totalAvailable }}</td>
                                <td colspan="3" style="padding:10px 14px;"></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-box-open" style="font-size:2rem;color:#d4e2f0;"></i>
                                    <p class="text-muted mt-2" style="font-size:0.85rem;">No inventory found for this product</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection