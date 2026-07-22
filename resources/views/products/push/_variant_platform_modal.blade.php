<div class="modal fade" id="variantPlatformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- HEADER --}}
            <div class="modal-header border-bottom bg-white py-3">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">Configure Variant</h4>
                    <small class="text-muted">Set platform wise stock, price and discount</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body bg-light">
                <input type="hidden" id="modalVariantId">

                {{-- ============================================= --}}
                {{-- TOP SUMMARY CARD --}}
                {{-- ============================================= --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center">

                            {{-- Product Info --}}
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <img id="modalProductImage"
                                         src="{{ asset('images/no-image.png') }}"
                                         class="rounded-3 border me-3"
                                         style="width:65px; height:65px; object-fit:cover;">
                                    <div>
                                        <h6 class="fw-bold mb-1" id="modalProductName">Product Name</h6>
                                        <div class="text-muted small">
                                            SKU: <span id="modalSku">-</span>
                                        </div>
                                        <div class="text-muted small">
                                            Brand: <span id="modalBrand">-</span> | 
                                            Category: <span id="modalCategory">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Variant --}}
                            <div class="col-md-2">
                                <div class="bg-light rounded-3 p-2 text-center">
                                    <small class="text-muted d-block">Variant</small>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span id="modalVariantColor" class="badge" style="background:#808080; color:white; width:30px; height:30px; border-radius:50%;"></span>
                                        <span class="fw-semibold" id="modalVariantValue">-</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Purchase Order --}}
                            <div class="col-md-3">
                                <div class="bg-light rounded-3 p-2">
                                    <div class="d-flex justify-content-around">
                                        <div class="text-center">
                                            <small class="text-muted d-block">PO Quantity</small>
                                            <h6 class="fw-bold text-success mb-0" id="modalPoQty">0</h6>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">PO Price</small>
                                            <h6 class="fw-bold text-primary mb-0">₹<span id="modalPoPrice">0</span></h6>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">PO Value</small>
                                            <h6 class="fw-bold text-dark mb-0">₹<span id="modalPoValue">0</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Master Stock --}}
                            <div class="col-md-3">
                                <div class="bg-success bg-opacity-10 rounded-3 p-2 text-center">
                                    <small class="text-muted d-block">Master Stock</small>
                                    <h4 class="fw-bold text-success mb-0" id="masterStock">0 Units</h4>
                                    <div class="d-flex justify-content-between mt-1 small">
                                        <span>Allocated: <strong id="allocatedStock">0</strong></span>
                                        <span>Remaining: <strong id="remainingStock">0</strong></span>
                                    </div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div id="masterProgress" class="progress-bar bg-success" style="width:0%;"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- PLATFORM SELECTION --}}
                {{-- ============================================= --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4" id="platformSelectionCard" style="display:none;">
                    <div class="card-header bg-white border-bottom py-2">
                        <h6 class="fw-bold mb-0">Select Sales Platforms</h6>
                        <small class="text-muted">Uncheck to disable and release allocated stock</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($platforms as $platform)
                                <label class="d-flex align-items-center gap-2 border rounded-3 px-3 py-2 cursor-pointer platform-option"
                                       style="background: #f8f9fa; transition: all 0.2s;"
                                       onmouseover="this.style.background='#e9ecef'"
                                       onmouseout="this.style.background='#f8f9fa'">
                                    
                                    <input type="checkbox"
                                           class="platform-checkbox"
                                           value="{{ $platform->id }}"
                                           data-platform-id="{{ $platform->id }}"
                                           data-platform-name="{{ $platform->name }}"
                                           id="platform_checkbox_{{ $platform->id }}">

                                    <div class="d-flex align-items-center gap-2">
                                        @php $name = strtolower($platform->name); @endphp
                                        @if(str_contains($name, 'amazon'))
                                            <span class="fw-bold" style="color:#232F3E;">Amazon</span>
                                        @elseif(str_contains($name, 'flipkart'))
                                            <span class="fw-bold" style="color:#2874F0;">Flipkart</span>
                                        @elseif(str_contains($name, 'website'))
                                            <span>🌐 <span class="fw-semibold">Own Website</span></span>
                                        @elseif(str_contains($name, 'offline'))
                                            <span>🏪 <span class="fw-semibold">Offline</span></span>
                                        @elseif(str_contains($name, 'meesho'))
                                            <span>🛍️ <span class="fw-semibold">Meesho</span></span>
                                        @else
                                            <span class="fw-semibold">{{ ucfirst($platform->name) }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- PLATFORM ALLOCATION & PRICING TABLE --}}
                {{-- ============================================= --}}
                <div id="platformPricingSection" style="display:none;">
                    @if(isset($platforms) && count($platforms) > 0)
                        <div class="row g-4 mt-1">
                            
                            {{-- LEFT: Table --}}
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-white border-bottom py-2">
                                        <h6 class="fw-bold mb-0">Platform Allocation & Pricing</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width:120px;">Platform</th>
                                                        <th style="width:80px;">Allocate Stock</th>
                                                        <th style="width:100px;">Selling Price (₹)</th>
                                                        <th style="width:100px;">Discount</th>
                                                        <th style="width:100px;">Final Price (₹)</th>
                                                        <th style="width:120px;">Total Selling (₹)</th>
                                                        <th style="width:80px;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="platformPricingTableBody">
                                                    @foreach($platforms as $platform)
                                                        <tr class="platform-pricing-row" id="platformRow_{{ $platform->id }}" style="display:none;">
                                                            <td colspan="7" class="p-2">
                                                                @include('products.push._platform_pricing',[
                                                                    'platform' => $platform,
                                                                    'variantId' => $currentVariantId ?? null,
                                                                    'purchaseOrderData' => $purchaseOrderData ?? []
                                                                ])
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Allocation Summary --}}
                            <div class="col-lg-4">
                                <div class="card border-0 shadow rounded-4 sticky-top" style="top:20px;">
                                    <div class="card-header bg-white border-bottom py-2">
                                        <h6 class="fw-bold mb-0">Allocation Summary</h6>
                                    </div>
                                    <div class="card-body" style="font-size:13px;">
                                        
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Master Stock</span>
                                            <strong id="summaryMasterStock">0 Units</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Total Allocated</span>
                                            <strong class="text-primary" id="summaryAllocated">0 Units</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Remaining</span>
                                            <strong class="text-success" id="summaryRemaining">0 Units</strong>
                                        </div>
                                        
                                        <hr class="my-2">

                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Total Purchase Value</span>
                                            <strong id="summaryPurchase">₹0</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Expected Revenue</span>
                                            <strong id="summaryRevenue">₹0</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Expected Profit</span>
                                            <strong class="text-success" id="summaryProfit">₹0</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Average Selling Price</span>
                                            <strong id="summaryAvgPrice">₹0 / Unit</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Expected Margin</span>
                                            <strong id="summaryMargin">0%</strong>
                                        </div>
                                        
                                        <hr class="my-2">

                                        <div class="mt-2">
                                            <div class="d-flex justify-content-between small">
                                                <span>Master: <strong id="summaryMasterSmall">0</strong></span>
                                                <span>Allocated: <strong id="summaryAllocatedSmall">0</strong></span>
                                                <span>Remaining: <strong id="summaryRemainingSmall">0</strong></span>
                                            </div>
                                            <div class="progress mt-1" style="height:6px;">
                                                <div id="summaryProgress" class="progress-bar bg-success" style="width:0%;"></div>
                                            </div>
                                            <div class="text-center small mt-2" id="summaryStatus">
                                                <span class="text-success">✅ Stock allocation is valid.</span>
                                            </div>
                                        </div>

                                        <div class="mt-2 p-2 bg-light rounded-3 small text-muted">
                                            <strong>Note:</strong> Discount is applied per unit.
                                            Example: Selling Price ₹500 - 10% = ₹450 per unit.
                                            For Qty 12 → Total = ₹450 x 12 = ₹5,400
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="alert alert-warning">No platforms configured.</div>
                    @endif
                </div>

                {{-- Add More Platform --}}
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary border-dashed">
                        + Add More Platform
                    </button>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-white border-top">
                <button class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success px-4" id="saveVariantData">
                    <i class="bi bi-check-circle me-1"></i> Save Variant
                </button>
            </div>

        </div>
    </div>
</div>