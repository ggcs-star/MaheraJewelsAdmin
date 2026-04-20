@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Orders Management</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage and track all customer orders</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-indigo-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Total Orders</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-indigo-600 mt-1">All orders</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-amber-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Pending</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</p>
            <p class="text-xs text-amber-600 mt-1">Awaiting confirmation</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-blue-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Processing</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['processing'] ?? 0 }}</p>
            <p class="text-xs text-blue-600 mt-1">Being processed</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all border border-emerald-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Delivered</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['delivered'] ?? 0 }}</p>
            <p class="text-xs text-emerald-600 mt-1">Completed orders</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Order Number..." class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base"/>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Processing</option>
                        <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
                        <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button class="w-full px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white; border: none;">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-36">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="text-sm font-bold text-gray-800">{{ $order->order_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ optional($order->user)->name ?? 'Guest' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ optional($order->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipped' => 'bg-indigo-100 text-indigo-700',
                                    'delivered' => 'bg-emerald-100 text-emerald-700',
                                    'confirmed' => 'bg-purple-100 text-purple-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($order->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-base font-bold text-gray-800">₹{{ number_format($order->total ?? 0,2) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-emerald-600 transition-colors rounded-lg hover:bg-emerald-50" title="Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No orders found</p>
                            <p class="text-gray-400 text-xs mt-1">Try adjusting your search or filter</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
            <div class="flex justify-between items-center flex-wrap gap-2">
                <div class="text-sm font-medium text-gray-500">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
            <div class="text-sm font-medium text-gray-500">Showing {{ $orders->count() }} order(s)</div>
        </div>
        @endif
    </div>
</div>
@endsection