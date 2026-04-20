@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Stock Management</h1>
        <p class="text-sm text-gray-500 mt-0.5">Monitor inventory and stock levels across platforms</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-all border border-indigo-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $products->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-all border border-rose-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-rose-700 uppercase tracking-wider mb-1">Total Stock</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $products->sum(fn($p) => $p->variants->sum('total_qty')) }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-rose-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Our Website</h3>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-700">{{ $products->total() }} products</span>
                </div>
                <div class="flex flex-wrap gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="productSearch" class="pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm w-64" placeholder="Search product...">
                    </div>
                    <select id="stockStatusFilter" class="px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white cursor-pointer">
                        <option value="all">All Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                        <option value="normal">In Stock</option>
                    </select>
                    <button id="exportExcelBtn" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm" style="background: #10b981; color: white;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($products as $product)
            @php
                $totalQty = $product->variants->sum('total_qty');
                $soldQty = $product->variants->sum('sold_qty');
                $remainingQty = $product->variants->sum('remaining_qty');
                $stockStatus = $remainingQty <= 0 ? 'out' : ($remainingQty <= 5 ? 'low' : 'normal');
                $productVariants = $product->variants;
                
                $productImage = 'https://placehold.co/52x52?text=📦';
                if($product->image_url && \App\Helpers\S3Helper::exists($product->image_url)) {
                    $productImage = \App\Helpers\S3Helper::url($product->image_url);
                } elseif($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                    $productImage = \App\Helpers\S3Helper::url($product->gallery_images[0]);
                }
            @endphp
            
            <div class="product-item" data-product-name="{{ strtolower($product->name) }}" data-stock-status="{{ $stockStatus }}" data-product-id="{{ $product->id }}">
                <div class="product-header px-5 py-3 cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="expand-icon w-6 text-gray-400 text-xs">
                                <svg class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                                <img src="{{ $productImage }}" width="48" height="48" class="object-cover rounded-lg" onerror="this.src='https://placehold.co/48x48?text=📦'">
                            </div>
                            <div>
                                <div class="text-base font-bold text-gray-800">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400">{{ $productVariants->count() }} variants</div>
                            </div>
                        </div>
                        <div class="flex gap-6 items-center">
                            <div class="text-center min-w-[60px]">
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Total</div>
                                <div class="text-base font-bold text-gray-800">{{ number_format($totalQty) }}</div>
                            </div>
                            <div class="text-center min-w-[60px]">
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Sold</div>
                                <div class="text-base font-bold text-rose-600">{{ number_format($soldQty) }}</div>
                            </div>
                            <div class="text-center min-w-[70px]">
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Remaining</div>
                                <div class="text-base font-bold {{ $remainingQty <= 0 ? 'text-gray-400' : ($remainingQty <= 5 ? 'text-amber-600' : 'text-emerald-600') }}">{{ number_format($remainingQty) }}</div>
                            </div>
                            <div>
                                @if($remainingQty <= 0)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Out of Stock</span>
                                @elseif($remainingQty <= 5)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Low Stock</span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">In Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="product-variants hidden bg-gray-50/30 border-t border-gray-100">
                    <div class="px-5 py-3">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[600px]">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-12">#</th>
                                        <th class="py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-16"></th>
                                        <th class="py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Variant</th>
                                        <th class="py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider w-24">Color</th>
                                        <th class="py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider w-20">Total</th>
                                        <th class="py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider w-20">Sold</th>
                                        <th class="py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider w-24">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productVariants as $idx => $variant)
                                    @php
                                        $variantImage = 'https://placehold.co/36x36?text=📦';
                                        if($variant->image_url && \App\Helpers\S3Helper::exists($variant->image_url)) {
                                            $variantImage = \App\Helpers\S3Helper::url($variant->image_url);
                                        } elseif($product->image_url && \App\Helpers\S3Helper::exists($product->image_url)) {
                                            $variantImage = \App\Helpers\S3Helper::url($product->image_url);
                                        } elseif($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                                            $variantImage = \App\Helpers\S3Helper::url($product->gallery_images[0]);
                                        }
                                        
                                        $variantName = $variant->value->value ?? $variant->value->name ?? 'Default';
                                        $colorHex = $variant->color ?? '';
                                    @endphp
                                    <tr class="border-b border-gray-100" data-color-name="{{ $variant->color_name }}">
                                        <td class="py-2 text-xs text-gray-400">{{ $idx + 1 }}</td>
                                        <td class="py-2">
                                            <img src="{{ $variantImage }}" width="36" height="36" class="object-cover rounded-lg border border-gray-200" onerror="this.src='https://placehold.co/36x36?text=📦'">
                                        </td>
                                        <td class="py-2">
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-200">{{ $variantName }}</span>
                                        </td>
                                        <td class="py-2">
                                            @if($colorHex)
                                                <span class="inline-block w-7 h-7 rounded-lg" style="background: {{ $colorHex }}; border: 1px solid #cbd5e1;"></span>
                                            @else
                                                <span class="text-xs text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-center text-sm text-gray-700">{{ number_format($variant->total_qty) }}</td>
                                        <td class="py-2 text-center text-sm text-rose-600">{{ number_format($variant->sold_qty) }}</td>
                                        <td class="py-2 text-center text-sm">
                                            @if($variant->remaining_qty <= 0)
                                                <span class="text-gray-400">{{ number_format($variant->remaining_qty) }}</span>
                                            @elseif($variant->remaining_qty <= 5)
                                                <span class="text-amber-600 font-semibold">{{ number_format($variant->remaining_qty) }}</span>
                                            @else
                                                <span class="text-emerald-600 font-semibold">{{ number_format($variant->remaining_qty) }}</span>
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
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-gray-500 text-sm">No stock data found</p>
            </div>
            @endforelse
        </div>

        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
            <div class="flex justify-between items-center flex-wrap gap-3">
                <div class="text-sm font-medium text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                    {{ $products->total() }} products | {{ $products->sum(fn($p) => $p->variants->sum('total_qty')) }} units
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
                <div class="flex gap-3">
                    <span class="text-xs flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> In Stock</span>
                    <span class="text-xs flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Low Stock</span>
                    <span class="text-xs flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Out of Stock</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-header:hover {
    background-color: #f9fafb;
}
.expand-icon svg {
    transition: transform 0.2s ease;
}
.product-item.open .expand-icon svg {
    transform: rotate(90deg);
}
.product-item.open .product-header {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.pagination {
    margin-bottom: 0;
    flex-wrap: wrap;
    justify-content: center;
}
.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    color: #8B2452;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    padding: 6px 12px;
}
.pagination .page-item.active .page-link {
    background: #8B2452;
    border-color: #8B2452;
    color: white;
}
.pagination .page-link:hover {
    background: #f3e8ff;
    border-color: #8B2452;
    color: #8B2452;
}
</style>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
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
        const productNameElem = productItem.querySelector('.product-header .text-base');
        const productName = productNameElem ? productNameElem.innerText : '';
        
        const variantsTable = productItem.querySelector('.product-variants table tbody');
        if (variantsTable) {
            const variantRows = variantsTable.querySelectorAll('tr');
            variantRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 7) {
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
                            colorValue = row.getAttribute('data-color-name') || '';
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