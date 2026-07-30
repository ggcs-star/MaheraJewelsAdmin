@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    * {
        font-family: 'Inter', sans-serif;
    }
    
    :root {
        --primary: #8B2452;
        --primary-dark: #6B1A3E;
        --primary-light: #FDF2F8;
        --secondary: #F4A261;
        --dark: #1A1A2E;
        --gray: #64748B;
        --gray-light: #94A3B8;
        --bg: #F1F5F9;
        --white: #FFFFFF;
        --border: #E2E8F0;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 32px rgba(0,0,0,0.12);
    }
    
    .dashboard-container {
        background: var(--bg);
        min-height: 100vh;
    }
    
    .stat-card {
        background: var(--white);
        border-radius: 24px;
        cursor: pointer;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 24px 24px 0 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }
    
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.05);
    }
    
    .stat-card-1 {
        background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%);
        border-left: 4px solid #EF4444;
    }
    .stat-card-2 {
        background: linear-gradient(135deg, #F0FDF4 0%, #FFFFFF 100%);
        border-left: 4px solid #10B981;
    }
    .stat-card-3 {
        background: linear-gradient(135deg, #FFFBEB 0%, #FFFFFF 100%);
        border-left: 4px solid #F59E0B;
    }
    .stat-card-4 {
        background: linear-gradient(135deg, #EEF2FF 0%, #FFFFFF 100%);
        border-left: 4px solid #6366F1;
    }
    
    .stat-card-1 .stat-icon { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #EF4444; }
    .stat-card-2 .stat-icon { background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #10B981; }
    .stat-card-3 .stat-icon { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #F59E0B; }
    .stat-card-4 .stat-icon { background: linear-gradient(135deg, #E0E7FF, #C7D2FE); color: #6366F1; }
    
    .stat-card-1 .stat-value { color: #EF4444; }
    .stat-card-2 .stat-value { color: #10B981; }
    .stat-card-3 .stat-value { color: #F59E0B; }
    .stat-card-4 .stat-value { color: #6366F1; }
    
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    
    .section-card {
        background: var(--white);
        border-radius: 24px;
        transition: all 0.3s ease;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }
    
    .section-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--secondary), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .section-card:hover::after {
        opacity: 1;
    }
    
    .section-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .section-title span {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        width: 5px;
        height: 24px;
        border-radius: 3px;
        display: inline-block;
    }
    
    .status-badge {
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.3px;
    }
    
    .stock-table-row {
        border-bottom: 1px solid var(--border);
        transition: all 0.2s ease;
    }
    
    .stock-table-row:hover {
        background: var(--primary-light);
    }
    
    .order-table-row {
        border-bottom: 1px solid var(--border);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .order-table-row:hover {
        background: linear-gradient(90deg, var(--primary-light), transparent);
    }
    
    .range-btn {
        transition: all 0.2s ease;
        cursor: pointer;
        padding: 6px 16px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
        background: #F1F5F9;
        color: var(--gray);
        border: 1px solid transparent;
    }
    
    .range-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(139, 36, 82, 0.3);
    }
    
    .range-btn:hover:not(.active) {
        background: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary);
    }
    
    .doughnut-container {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
    }
    
    canvas {
        max-width: 100%;
        height: auto;
    }
    
    .footer-card {
        background: linear-gradient(135deg, var(--white), #FAFAFA);
        border-radius: 24px;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }
    
    .footer-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: radial-gradient(circle at 0% 0%, rgba(139,36,82,0.03), transparent);
        pointer-events: none;
    }
    
    thead {
        background: linear-gradient(90deg, #F8FAFC, #F1F5F9);
    }
    
    thead th {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .text-gray-800 {
        color: var(--dark) !important;
    }
    
    .text-gray-500 {
        color: var(--gray) !important;
    }
    
    @media (max-width: 768px) {
        .dashboard-container { padding: 16px; }
        .stat-card { padding: 16px; }
        .stat-icon { width: 44px; height: 44px; }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-card .stat-value { font-size: 24px; }
        .section-card { padding: 16px; }
        .doughnut-container { width: 160px; height: 160px; }
    }
    
    @media (max-width: 480px) {
        .dashboard-container { padding: 12px; }
        .stat-card { padding: 14px; }
        .stat-icon { width: 40px; height: 40px; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-card .stat-value { font-size: 20px; }
        .section-card { padding: 14px; }
        .range-btn { padding: 4px 12px; font-size: 10px; }
        .doughnut-container { width: 140px; height: 140px; }
    }
</style>

<div class="dashboard-container p-4 md:p-6 lg:p-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-500 text-sm mt-2">Welcome back, <span class="font-semibold text-[#8B2452]">{{ Auth::user()->name }}</span></p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm flex items-center gap-2 transition-all hover:shadow-md" style="background: #8B2452; color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card stat-card-1 p-5" onclick="window.location.href='{{ route('admin.products.index') }}'">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Products</p>
                    <p class="text-2xl font-bold stat-value">{{ number_format($totalProducts) }}</p>
                    <p class="text-gray-400 text-xs mt-2">All inventory items</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-2 p-5" onclick="window.location.href='{{ route('admin.categories.index') }}'">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Categories</p>
                    <p class="text-2xl font-bold stat-value">{{ number_format($totalCategories) }}</p>
                    <p class="text-gray-400 text-xs mt-2">Active categories</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-3 p-5" onclick="window.location.href='{{ route('admin.stock.index') }}'">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-700 text-sm mb-1">Products in Stock</p>
                    <p class="text-2xl font-bold stat-value">{{ number_format($lowStockCount) }}</p>
                    <p class="text-gray-400 text-xs mt-2">With variants</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-4 p-5" onclick="window.location.href='{{ route('admin.orders.index') }}'">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Orders</p>
                    <p class="text-2xl font-bold stat-value">{{ number_format($totalOrders) }}</p>
                    <p class="text-gray-400 text-xs mt-2">All time orders</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="section-card p-5">
            <div class="flex justify-between items-center mb-4">
                <div class="section-title mb-0">
                    <span></span>
                    Sales Overview
                </div>
                <div class="flex gap-2">
                    <button class="range-btn {{ $range == '7' ? 'active' : '' }}" data-range="7">7 Days</button>
                    <button class="range-btn {{ $range == '30' ? 'active' : '' }}" data-range="30">30 Days</button>
                    <button class="range-btn {{ $range == '90' ? 'active' : '' }}" data-range="90">90 Days</button>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="section-card p-5">
            <div class="section-title">
                <span></span>
                Order Status Distribution
            </div>
            <div class="doughnut-container">
                <canvas id="orderRoundChart"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-3 mt-5">
                <div class="text-center p-2 rounded-lg" style="background: #fdf2f8;">
                    <p class="text-xs text-gray-500">Confirmed</p>
                    <p class="text-lg font-bold" style="color: #8B2452;">{{ number_format($orderStats['confirmed']) }}</p>
                </div>
                <div class="text-center p-2 rounded-lg" style="background: #fdf2f8;">
                    <p class="text-xs text-gray-500">Delivered</p>
                    <p class="text-lg font-bold" style="color: #8B2452;">{{ number_format($orderStats['delivered']) }}</p>
                </div>
                <div class="text-center p-2 rounded-lg" style="background: #fdf2f8;">
                    <p class="text-xs text-gray-500">Cancelled</p>
                    <p class="text-lg font-bold" style="color: #8B2452;">{{ number_format($orderStats['cancelled']) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-3 pt-2 border-t border-gray-100">
                <div class="text-center">
                    <p class="text-xs text-gray-400">Pending</p>
                    <p class="text-sm font-semibold" style="color: #f59e0b;">{{ number_format($orderStats['pending']) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400">Processing</p>
                    <p class="text-sm font-semibold" style="color: #8b5cf6;">{{ number_format($orderStats['processing']) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400">Shipped</p>
                    <p class="text-sm font-semibold" style="color: #ec489a;">{{ number_format($orderStats['shipped']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="section-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="section-title mb-0">
                    <span></span>
                    Products Stock
                </div>
                <a href="{{ route('admin.stock.index') }}" class="text-xs font-semibold" style="color: #8B2452;">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Product Name</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Stock</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts->take(5) as $index => $product)
                            @php
                                $totalStockQty = $product->variants->sum('quantity');
                            @endphp
                            <tr class="stock-table-row">
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ Str::limit($product->name ?? 'Unknown', 25) }}</td>
                                <td class="px-5 py-3 text-sm font-semibold" style="color: #10B981;">{{ $totalStockQty }} units</td>
                                <td class="px-5 py-3">
                                    @if($totalStockQty <= 5)
                                        <span class="status-badge" style="background: #fef3c7; color: #d97706;">⚠️ Low Stock</span>
                                    @else
                                        <span class="status-badge" style="background: #dcfce7; color: #10B981;">✓ In Stock</span>
                                    @endif
                                  </td>
                              </tr>
                        @empty
                              <tr>
                                 <td colspan="4" class="px-5 py-8 text-center text-gray-400">No products found</td>
                              </tr>
                        @endforelse
                     </tbody>
                  </table>
             </div>
         </div>

         <div class="section-card overflow-hidden">
             <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                 <div class="section-title mb-0">
                     <span></span>
                     Recent Orders
                 </div>
                 <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold" style="color: #8B2452;">View All →</a>
             </div>
             <div class="overflow-x-auto">
                 <table class="w-full">
                     <thead style="background: #f8fafc;">
                         <tr>
                             <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Order #</th>
                             <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Amount</th>
                             <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                             <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                          </tr>
                     </thead>
                     <tbody>
    @forelse($recentOrders->take(5) as $order)
    <tr class="order-table-row cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
        <td class="px-5 py-3 text-sm font-medium text-gray-800">
            @if($order->is_amazon ?? false)
                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">Amazon</span>
            @endif
            {{ $order->order_number }}
        </td>
        <td class="px-5 py-3 text-sm font-semibold text-gray-800">
            ₹{{ number_format($order->total ?? $order->order_total ?? 0, 2) }}
        </td>
        <td class="px-5 py-3 text-sm text-gray-500">
            {{ isset($order->created_at) ? $order->created_at->format('d M Y') : (isset($order->purchase_date) ? date('d M Y', strtotime($order->purchase_date)) : 'N/A') }}
        </td>
        <td class="px-5 py-3">
            @php
                $status = $order->status ?? $order->order_status ?? 'pending';
                $statusClass = match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'confirmed' => 'bg-blue-100 text-blue-700',
                    'processing' => 'bg-purple-100 text-purple-700',
                    'shipped' => 'bg-pink-100 text-pink-700',
                    'delivered' => 'bg-green-100 text-green-700',
                    'Canceled', 'Cancelled' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700'
                };
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ ucfirst($status) }}
            </span>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="px-5 py-8 text-center text-gray-400">No orders found</td>
    </tr>
    @endforelse
</tbody>
                  </table>
             </div>
         </div>
     </div>

     <div class="footer-card p-5">
         <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-center">
             <div>
                 <p class="text-xl font-bold text-gray-800">{{ number_format($totalProducts) }}</p>
                 <p class="text-xs text-gray-500 mt-1">Total Products</p>
             </div>
             <div>
                 <p class="text-xl font-bold text-gray-800">{{ number_format($orderStats['delivered']) }}</p>
                 <p class="text-xs text-gray-500 mt-1">Orders Delivered</p>
             </div>
             <div>
                 <p class="text-xl font-bold text-gray-800">{{ number_format($lowStockCount) }}</p>
                 <p class="text-xs text-gray-500 mt-1">Products in Stock</p>
             </div>
             <div>
                 <p class="text-xl font-bold text-gray-800">₹{{ number_format($currentMonthSales, 2) }}</p>
                 <p class="text-xs text-gray-500 mt-1">This Month Revenue</p>
             </div>
         </div>
     </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let salesChart = null;
    let currentRange = {{ $range }};
    
    async function updateChart(range) {
        try {
            const response = await fetch(`/admin/dashboard-data?range=${range}`);
            const data = await response.json();
            
            if (data.success) {
                if (salesChart) {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.salesData;
                    salesChart.update();
                }
                
                document.querySelectorAll('.range-btn').forEach(btn => {
                    if (btn.dataset.range == range) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                
                currentRange = range;
                history.pushState({}, '', `/admin/dashboard?range=${range}`);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    document.querySelectorAll('.range-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const range = this.dataset.range;
            if (range != currentRange) {
                updateChart(range);
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Overview Chart - Theme colors
        var salesCtx = document.getElementById('salesChart').getContext('2d');
        var labels = {!! json_encode($labels) !!};
        var salesData = {!! json_encode($salesData) !!};
        
        salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Confirmed',
                        data: salesData.map((val, i) => Math.floor(val * 0.6)),
                        borderColor: '#8B2452',      // Theme primary color
                        backgroundColor: 'rgba(139, 36, 82, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#8B2452',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Delivered',
                        data: salesData.map((val, i) => Math.floor(val * 0.3)),
                        borderColor: '#F4A261',      // Theme secondary color
                        backgroundColor: 'rgba(244, 162, 97, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#F4A261',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Cancelled',
                        data: salesData.map((val, i) => Math.floor(val * 0.1)),
                        borderColor: '#6B1A3E',      // Theme primary dark
                        backgroundColor: 'rgba(107, 26, 62, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#6B1A3E',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11, weight: '500' },
                            padding: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Orders',
                            font: { size: 11, weight: '500' },
                            color: '#64748B'
                        },
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#E2E8F0',
                            drawBorder: false
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: { size: 11, weight: '500' },
                            color: '#64748B'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: { size: 10 }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Order Status Distribution Chart - Theme colors
        var orderCtx = document.getElementById('orderRoundChart').getContext('2d');
        var orderStats = {!! json_encode($orderStats) !!};
        
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [orderStats.confirmed, orderStats.delivered, orderStats.cancelled],
                    backgroundColor: ['#8B2452', '#F4A261', '#6B1A3E'],  // Theme colors
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: { size: 10 },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection