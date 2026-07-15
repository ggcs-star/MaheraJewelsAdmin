@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold" style="font-size: 1.6rem; color: #0a1e2f;">
                <i class="fas fa-box me-2" style="color: #8B2452;"></i> Inventory Details
            </h1>
            <p class="text-muted">{{ $variant->product->name }} - {{ $variant->sku_suffix }}</p>
        </div>
        <a href="{{ url('admin/inventory/dashboard') }}" class="btn btn-outline-secondary rounded-5 px-4">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted" style="font-size: 0.7rem;">PO Quantity</div>
                <h4 class="fw-bold">{{ $poQty }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted" style="font-size: 0.7rem;">Website Pushed</div>
                <h4 class="fw-bold text-primary">{{ $websitePushed }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted" style="font-size: 0.7rem;">Offline Pushed</div>
                <h4 class="fw-bold text-success">{{ $offlinePushed }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted" style="font-size: 0.7rem;">Current Stock</div>
                <h4 class="fw-bold">{{ $variant->quantity }}</h4>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom: 2px solid #f0f6fa;">
            <h6 class="mb-0 fw-bold" style="color: #0a1e2f;">
                <i class="fas fa-history me-2" style="color: #8B2452;"></i> Stock Movement History
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead style="background: #f8fcff; border-bottom: 2px solid #e9f0f5;">
                        <tr>
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">Platform</th>
                            <th class="px-3 py-3 text-center">Movement</th>
                            <th class="px-3 py-3 text-center">Quantity</th>
                            <th class="px-3 py-3 text-center">Balance</th>
                            <th class="px-3 py-3">Reference</th>
                            <th class="px-3 py-3">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockMovements as $movement)
                        <tr>
                            <td class="px-3 py-3">{{ $movement->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-3 py-3">{{ $movement->platform->name ?? '-' }}</td>
                            <td class="px-3 py-3 text-center">
                                @if($movement->movement == 'IN')
                                    <span class="badge bg-success rounded-5 px-3 py-2">📥 IN</span>
                                @else
                                    <span class="badge bg-danger rounded-5 px-3 py-2">📤 OUT</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center fw-bold">{{ $movement->quantity }}</td>
                            <td class="px-3 py-3 text-center">{{ $movement->balance }}</td>
                            <td class="px-3 py-3">
                                <span class="badge bg-light text-dark border">{{ $movement->reference_type }}</span>
                                <span class="text-muted">#{{ $movement->reference_id }}</span>
                            </td>
                            <td class="px-3 py-3 text-muted">{{ $movement->remarks ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open" style="font-size: 2rem; color: #d4e2f0;"></i>
                                <p class="mt-2">No stock movements found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection