@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4" style="background: #f1f5f9; min-height: 100vh;">
    <div class="mb-4">
        <h1 class="h3 mb-1" style="color: #0f172a; font-weight: 700;">Stock Management</h1>
        <p class="text-muted small mb-0">Monitor inventory and stock levels across platforms</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500;">Total Products</p>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 32px;">{{ $products->total() }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 16px; padding: 12px;">
                            <i class="fas fa-boxes" style="color: white; font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500;">Total Stock</p>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 32px;">{{ $products->sum(fn($p) => $p->variants->sum('total_qty')) }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 16px; padding: 12px;">
                            <i class="fas fa-cubes" style="color: white; font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border: none; border-radius: 24px; background: #ffffff;">
        <div class="card-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; border-radius: 24px 24px 0 0; padding: 20px 24px;">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background: #eef2ff; padding: 8px; border-radius: 12px;">
                            <i class="fas fa-store" style="color: #4f46e5; font-size: 16px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #0f172a;">Our Website</h5>
                        <span class="badge" style="background: #eef2ff; color: #4f46e5; padding: 4px 12px; border-radius: 30px; font-size: 11px;">{{ $products->total() }} products</span>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex gap-3 justify-content-end">
                        <div class="position-relative" style="width: 260px;">
                            <i class="fas fa-search position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;"></i>
                            <input type="text" id="productSearch" class="form-control" placeholder="Search product..." style="padding-left: 42px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 13px; height: 42px;">
                        </div>
                        <select id="stockStatusFilter" class="form-select" style="border-radius: 12px; border: 1px solid #e2e8f0; width: 140px; font-size: 13px; height: 42px; cursor: pointer;">
                            <option value="all">All Stock</option>
                            <option value="low">Low Stock</option>
                            <option value="out">Out of Stock</option>
                            <option value="normal">In Stock</option>
                        </select>
                        <button id="exportExcelBtn" class="btn" style="background: #10b981; color: white; border-radius: 12px; padding: 0 18px; font-size: 13px; font-weight: 500; height: 42px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @forelse($products as $product)
            @php
                $totalQty = $product->variants->sum('total_qty');
                $soldQty = $product->variants->sum('sold_qty');
                $remainingQty = $product->variants->sum('remaining_qty');
                $stockStatus = $remainingQty <= 0 ? 'out' : ($remainingQty <= 5 ? 'low' : 'normal');
                $productVariants = $product->variants;
                
                $productImage = 'https://placehold.co/52x52?text=📦';
                if($product->image_url && Storage::disk('s3')->exists($product->image_url)) {
                    $productImage = Storage::disk('s3')->url($product->image_url);
                } elseif($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                    $productImage = Storage::disk('s3')->url($product->gallery_images[0]);
                }
            @endphp
            
            <div class="product-item border-bottom" style="border-color: #f1f5f9 !important;" data-product-name="{{ strtolower($product->name) }}" data-stock-status="{{ $stockStatus }}" data-product-id="{{ $product->id }}">
                <div class="product-header" style="padding: 16px 24px; cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3" style="flex: 1;">
                            <div class="expand-icon" style="width: 24px; color: #94a3b8; font-size: 12px;">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                            <div style="width: 52px; height: 52px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                <img src="{{ $productImage }}" width="48" height="48" style="object-fit: cover; border-radius: 10px;" onerror="this.src='https://placehold.co/48x48?text=📦'">
                            </div>
                            <div>
                                <div class="fw-bold" style="color: #0f172a; font-size: 15px;">{{ $product->name }}</div>
                                <div class="text-muted" style="font-size: 12px;">{{ $productVariants->count() }} variants</div>
                            </div>
                        </div>
                        <div class="d-flex gap-4 align-items-center">
                            <div class="text-center" style="min-width: 60px;">
                                <div style="font-size: 10px; color: #64748b;">Total</div>
                                <div class="fw-bold" style="color: #0f172a; font-size: 16px;">{{ number_format($totalQty) }}</div>
                            </div>
                            <div class="text-center" style="min-width: 60px;">
                                <div style="font-size: 10px; color: #64748b;">Sold</div>
                                <div class="fw-bold" style="color: #ef4444; font-size: 16px;">{{ number_format($soldQty) }}</div>
                            </div>
                            <div class="text-center" style="min-width: 70px;">
                                <div style="font-size: 10px; color: #64748b;">Remaining</div>
                                <div class="fw-bold" style="color: {{ $remainingQty <= 0 ? '#94a3b8' : ($remainingQty <= 5 ? '#f97316' : '#10b981') }}; font-size: 16px;">{{ number_format($remainingQty) }}</div>
                            </div>
                            <div>
                                @if($remainingQty <= 0)
                                    <span style="background: #fef2f2; color: #dc2626; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 600;">Out of Stock</span>
                                @elseif($remainingQty <= 5)
                                    <span style="background: #fff7ed; color: #ea580c; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 600;">Low Stock</span>
                                @else
                                    <span style="background: #f0fdf4; color: #16a34a; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 600;">In Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="product-variants" style="display: none; background: #fafcff; border-top: 1px solid #f1f5f9;">
                    <div class="px-4 py-3">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <th width="50" style="font-size: 11px; color: #64748b;">#</th>
                                        <th width="60" style="font-size: 11px; color: #64748b;"></th>
                                        <th style="font-size: 11px; color: #64748b;">Variant</th>
                                        <th width="120" style="font-size: 11px; color: #64748b;">Color</th>
                                        <th class="text-center" width="80" style="font-size: 11px; color: #64748b;">Total</th>
                                        <th class="text-center" width="80" style="font-size: 11px; color: #64748b;">Sold</th>
                                        <th class="text-center" width="100" style="font-size: 11px; color: #64748b;">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productVariants as $idx => $variant)
                                    @php
                                        $variantImage = 'https://placehold.co/36x36?text=📦';
                                        if($variant->image_url && Storage::disk('s3')->exists($variant->image_url)) {
                                            $variantImage = Storage::disk('s3')->url($variant->image_url);
                                        } elseif($product->image_url && Storage::disk('s3')->exists($product->image_url)) {
                                            $variantImage = Storage::disk('s3')->url($product->image_url);
                                        } elseif($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                                            $variantImage = Storage::disk('s3')->url($product->gallery_images[0]);
                                        }
                                        
                                        $variantName = $variant->value->value ?? $variant->value->name ?? 'Default';
                                        $colorHex = $variant->color ?? '';
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="font-size: 12px; color: #94a3b8;">{{ $idx + 1 }}</td>
                                        <td>
                                            <img src="{{ $variantImage }}" width="36" height="36" style="object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;" onerror="this.src='https://placehold.co/36x36?text=📦'">
                                        </td>
                                        <td>
                                            <span style="background: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 12px; border: 1px solid #e2e8f0;">
                                                {{ $variantName }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($colorHex)
                                                <span style="display: inline-block; width: 28px; height: 28px; border-radius: 8px; background: {{ $colorHex }}; border: 1px solid #cbd5e1;"></span>
                                            @else
                                                <span style="color: #cbd5e1;">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ number_format($variant->total_qty) }}</td>
                                        <td class="text-center" style="color: #ef4444;">{{ number_format($variant->sold_qty) }}</td>
                                        <td class="text-center">
                                            @if($variant->remaining_qty <= 0)
                                                <span style="color: #94a3b8;">{{ number_format($variant->remaining_qty) }}</span>
                                            @elseif($variant->remaining_qty <= 5)
                                                <span style="color: #f97316; font-weight: 500;">{{ number_format($variant->remaining_qty) }}</span>
                                            @else
                                                <span style="color: #10b981; font-weight: 500;">{{ number_format($variant->remaining_qty) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No stock data found</p>
            </div>
            @endforelse
        </div>

        <div class="card-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; border-radius: 0 0 24px 24px; padding: 14px 24px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-database me-1"></i> {{ $products->total() }} products | {{ $products->sum(fn($p) => $p->variants->sum('total_qty')) }} units
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
                <div class="d-flex gap-3">
                    <span style="font-size: 11px;"><span style="display: inline-block; width: 10px; height: 10px; background: #10b981; border-radius: 50%; margin-right: 6px;"></span> In Stock</span>
                    <span style="font-size: 11px;"><span style="display: inline-block; width: 10px; height: 10px; background: #f97316; border-radius: 50%; margin-right: 6px;"></span> Low Stock</span>
                    <span style="font-size: 11px;"><span style="display: inline-block; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; margin-right: 6px;"></span> Out of Stock</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-header:hover {
        background-color: #f8fafc;
    }
    .expand-icon {
        transition: transform 0.2s ease;
    }
    .product-item.open .expand-icon {
        transform: rotate(90deg);
    }
    .product-item.open .product-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        outline: none;
    }
    .table td, .table th {
        vertical-align: middle;
        padding: 10px 8px;
    }
    #exportExcelBtn:hover {
        background: #059669;
        transition: 0.2s;
    }
    .pagination {
        margin-bottom: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 3px;
        color: #4f46e5;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        padding: 6px 12px;
    }
    .pagination .page-item.active .page-link {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
    .pagination .page-link:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4f46e5;
    }
</style>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
function getColorName(hex) {
    const colors = {
        '#2069df': 'Blue', '#df2a2a': 'Red', '#b75c5c': 'Rose', '#2fbc59': 'Green', '#8a8728': 'Olive',
        '#a73939': 'Maroon', '#1f48a8': 'Navy Blue', '#313f34': 'Dark Green', '#ba2c2c': 'Crimson Red',
        '#993d3d': 'Brown Red', '#000000': 'Black', '#FFFFFF': 'White', '#FF0000': 'Red', '#00FF00': 'Green',
        '#0000FF': 'Blue', '#FFFF00': 'Yellow', '#FF00FF': 'Magenta', '#00FFFF': 'Cyan', '#808080': 'Gray',
        '#800000': 'Maroon', '#808000': 'Olive', '#008000': 'Green', '#800080': 'Purple', '#000080': 'Navy',
        '#008080': 'Teal', '#C0C0C0': 'Silver', '#FFA500': 'Orange', '#FFC0CB': 'Pink', '#FFD700': 'Gold',
        '#A52A2A': 'Brown', '#D2691E': 'Chocolate', '#DC143C': 'Crimson', '#EE82EE': 'Violet', '#F08080': 'Light Coral'
    };
    
    const upperHex = hex.toUpperCase();
    if (colors[upperHex]) {
        return colors[upperHex];
    }
    
    const hexClean = upperHex.replace('#', '');
    if (hexClean === '000000') return 'Black';
    if (hexClean === 'FFFFFF') return 'White';
    if (hexClean.length === 6) {
        const r = parseInt(hexClean.substring(0,2), 16);
        const g = parseInt(hexClean.substring(2,4), 16);
        const b = parseInt(hexClean.substring(4,6), 16);
        
        if (r > 200 && g < 100 && b < 100) return 'Red';
        if (r < 100 && g > 200 && b < 100) return 'Green';
        if (r < 100 && g < 100 && b > 200) return 'Blue';
        if (r > 200 && g > 200 && b < 100) return 'Yellow';
        if (r > 200 && g < 100 && b > 200) return 'Magenta';
        if (r < 100 && g > 200 && b > 200) return 'Cyan';
        if (r > 200 && g > 100 && b < 150) return 'Orange';
        if (r > 150 && g < 100 && b < 100) return 'Dark Red';
        if (r < 100 && g > 150 && b < 100) return 'Dark Green';
        if (r < 100 && g < 100 && b > 150) return 'Dark Blue';
        if (r > 100 && g > 100 && b > 100 && r < 200 && g < 200 && b < 200) return 'Gray';
        if (r < 50 && g < 50 && b < 50) return 'Black';
        if (r > 200 && g > 200 && b > 200) return 'White';
    }
    return hex;
}

document.querySelectorAll('.product-header').forEach(header => {
    header.addEventListener('click', function(e) {
        e.stopPropagation();
        const parent = this.closest('.product-item');
        const variants = parent.querySelector('.product-variants');
        const isOpen = variants.style.display === 'block';
        
        document.querySelectorAll('.product-variants').forEach(v => v.style.display = 'none');
        document.querySelectorAll('.product-item').forEach(p => p.classList.remove('open'));
        
        if (!isOpen) {
            variants.style.display = 'block';
            parent.classList.add('open');
        }
    });
});

const searchInput = document.getElementById('productSearch');
const statusFilter = document.getElementById('stockStatusFilter');
const productItems = document.querySelectorAll('.product-item');

function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    
    productItems.forEach(item => {
        const productName = item.getAttribute('data-product-name') || '';
        const stockStatus = item.getAttribute('data-stock-status') || '';
        
        let show = true;
        if (searchTerm && !productName.includes(searchTerm)) show = false;
        if (show && statusValue !== 'all' && stockStatus !== statusValue) show = false;
        
        item.style.display = show ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterProducts);
statusFilter.addEventListener('change', filterProducts);

document.getElementById('exportExcelBtn').addEventListener('click', function() {
    const exportData = [];
    exportData.push(['Product Name', 'Variant Name', 'Color', 'Total Quantity', 'Sold Quantity', 'Remaining Quantity', 'Stock Status']);
    
    const allProductItems = document.querySelectorAll('.product-item');
    
    allProductItems.forEach(productItem => {
        const productNameElem = productItem.querySelector('.product-header .fw-bold');
        const productName = productNameElem ? productNameElem.innerText : '';
        
        const variantsTable = productItem.querySelector('.product-variants table tbody');
        if (variantsTable) {
            const variantRows = variantsTable.querySelectorAll('tr');
            variantRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    let variantName = '';
                    let colorValue = '';
                    let totalQty = '';
                    let soldQty = '';
                    let remainingQty = '';
                    
                    const variantSpan = cells[2]?.querySelector('span');
                    variantName = variantSpan ? variantSpan.innerText.trim() : (cells[2]?.innerText.trim() || 'Default');
                    
                    const colorSpan = cells[3]?.querySelector('span[style*="background"]');
                    if (colorSpan) {
                        let bgColor = colorSpan.style.background;
                        let hex = '';
                        if (bgColor.includes('rgb')) {
                            const rgb = bgColor.match(/\d+/g);
                            if (rgb && rgb.length >= 3) {
                                hex = '#' + ((1 << 24) + (parseInt(rgb[0]) << 16) + (parseInt(rgb[1]) << 8) + parseInt(rgb[2])).toString(16).slice(1);
                            }
                        } else if (bgColor.startsWith('#')) {
                            hex = bgColor;
                        }
                        if (hex) {
                            colorValue = getColorName(hex);
                        } else {
                            colorValue = '';
                        }
                    } else {
                        colorValue = '';
                    }
                    
                    totalQty = cells[4]?.innerText.trim().replace(/,/g, '') || '0';
                    soldQty = cells[5]?.innerText.trim().replace(/,/g, '') || '0';
                    remainingQty = cells[6]?.innerText.trim().replace(/,/g, '') || '0';
                    
                    let stockStatusText = '';
                    const remainingNum = parseInt(remainingQty) || 0;
                    if (remainingNum <= 0) stockStatusText = 'Out of Stock';
                    else if (remainingNum <= 5) stockStatusText = 'Low Stock';
                    else stockStatusText = 'In Stock';
                    
                    exportData.push([productName, variantName, colorValue, totalQty, soldQty, remainingQty, stockStatusText]);
                }
            });
        }
    });
    
    if (exportData.length > 1) {
        const ws = XLSX.utils.aoa_to_sheet(exportData);
        ws['!cols'] = [{wch:30},{wch:25},{wch:20},{wch:12},{wch:12},{wch:14},{wch:14}];
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Stock Report');
        XLSX.writeFile(wb, 'stock_report_' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.xlsx');
    } else {
        alert('No data to export');
    }
});
</script>
@endsection