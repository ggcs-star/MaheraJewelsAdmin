@extends('layouts.admin')

@section('content')
    @php
    use App\Helpers\S3Helper;
    @endphp
    @php
        use Illuminate\Support\Facades\Storage;
        
        $meta = $order->payment->payment_meta ?? null;
        $subtotal = $meta['subtotal'] ?? $order->subtotal;
        $discount = $meta['discount'] ?? $order->discount;
        $tax = $meta['tax'] ?? $order->tax;
        $shipping = $meta['shipping'] ?? $order->shipping;
        $platformFee = $meta['platform_fee'] ?? $order->platform_fee;
        $total = $meta['total'] ?? $order->total;
    @endphp

    <div class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-[#8B2452] transition-colors">Orders</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">Order Details</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Order Details</h1>
                <p class="text-sm text-gray-500 mt-0.5">Order ID: #{{ $order->order_number ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all border border-gray-200 text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: #dc2626; color: white;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Invoice
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <h3 class="text-base font-bold text-gray-800">Order Items</h3>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($order->items as $item)
                            <div class="p-4 flex gap-4 hover:bg-gray-50/50 transition-colors">
                                <div class="flex-shrink-0">
                                    <img src="{{ $item->image ? (str_starts_with($item->image, 'http') ? $item->image : S3Helper::url($item->image)) : (optional($item->product)->image_url ? S3Helper::url($item->product->image_url) : asset('images/no-image.png')) }}" 
                                         class="w-16 h-16 rounded-lg border border-gray-200 object-cover shadow-sm" 
                                         alt="{{ $item->product_name ?? 'Product' }}" 
                                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                        <div>
                                            <h4 class="text-base font-bold text-gray-800">{{ $item->product_name ?? optional($item->product)->name ?? 'Product' }}</h4>
                                            <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $item->sku ?? 'N/A' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                                            <div class="text-base font-bold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <p class="text-gray-500 text-sm">No items found in this order</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-base font-bold text-gray-800">Price Details</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-800">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-sm text-emerald-600">
                                <span>Discount</span>
                                <span>- ₹{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-800">₹{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-800">₹{{ number_format($shipping, 2) }}</span>
                        </div>
                        @if($platformFee > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Platform Fee</span>
                                <span class="font-medium text-gray-800">₹{{ number_format($platformFee, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-gray-100 my-2"></div>
                        <div class="flex justify-between text-base font-bold">
                            <span class="text-gray-800">Total</span>
                            <span class="text-[#8B2452]">₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <h3 class="text-base font-bold text-gray-800">Update Status</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                            @csrf
                            <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white mb-3">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="w-full py-2 rounded-lg text-sm font-semibold transition-all shadow-md" style="background: var(--primary-light); color: white; border: none;">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <h3 class="text-base font-bold text-gray-800">Delivery Information</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Full Name</p>
                                <p class="text-sm font-semibold text-gray-800">{{ optional($order->shippingAddress)->full_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Phone</p>
                                <p class="text-sm font-semibold text-gray-800">{{ optional($order->shippingAddress)->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Address</p>
                                <p class="text-sm text-gray-700">
                                    {{ optional($order->shippingAddress)->address_line_1 ?? 'N/A' }}<br>
                                    {{ optional($order->shippingAddress)->city }}, {{ optional($order->shippingAddress)->state }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection