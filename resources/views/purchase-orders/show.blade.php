@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Purchase Order Details</h2>
            <p class="text-sm text-gray-500 mt-1">View purchase order information</p>
        </div>
        <div class="flex gap-3 mt-4 sm:mt-0">
            <a href="{{ admin_route('purchase-orders.edit', $purchaseOrder->id) }}"
               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-white hover:shadow-lg hover:shadow-amber-500/30 transition-all duration-200 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ admin_route('purchase-orders.index') }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-5 py-4 rounded-xl mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- PO Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-white">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        PO Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">PO Number</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->po_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Invoice Number</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->invoice_number ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Purchase Date</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ date('d M Y', strtotime($purchaseOrder->purchase_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Payment Method</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->payment_method ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Status</p>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-medium
                                {{ $purchaseOrder->status == 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $purchaseOrder->status == 'ordered' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $purchaseOrder->status == 'received' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $purchaseOrder->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($purchaseOrder->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Total Amount</p>
                            <p class="font-bold text-[#8B2452] text-lg mt-1">₹ {{ number_format($purchaseOrder->grand_total, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Supplier Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50/80 to-white">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2M5 9h14v10H5V9z"/>
                        </svg>
                        Supplier Information
                    </h3>
                </div>
                <div class="p-6">
                    @if($purchaseOrder->supplier)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Name</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Company</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->company_name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Phone</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Email</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">GST</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->gst_number ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Payment Terms</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->payment_terms ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Address</p>
                                <p class="font-semibold text-gray-800 mt-1">{{ $purchaseOrder->supplier->address ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider">City / State</p>
                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $purchaseOrder->supplier->city ?? '' }}
                                    {{ $purchaseOrder->supplier->state ?? '' }}
                                    {{ $purchaseOrder->supplier->country ?? '' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No supplier information available</p>
                    @endif
                </div>
            </div>

            {{-- Products Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50/80 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                        </h3>
                        <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Total: {{ $purchaseOrder->items->count() }}</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th class="border-b border-gray-200 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                                <th class="border-b border-gray-200 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Variant</th>
                                <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrder->items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                    <td class="border-b border-gray-100 px-4 py-3 text-sm text-gray-700">{{ $item->product->name ?? '-' }}</td>
                                    <td class="border-b border-gray-100 px-4 py-3 text-sm text-gray-700">
                                        @if($item->variant)
                                            {{ $item->variant->variant->name ?? '' }} : {{ $item->variant->value->value ?? '' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border-b border-gray-100 px-4 py-3 text-center text-sm text-gray-700">₹ {{ number_format($item->purchase_price, 2) }}</td>
                                    <td class="border-b border-gray-100 px-4 py-3 text-center text-sm text-gray-700">{{ $item->quantity }}</td>
                                    <td class="border-b border-gray-100 px-4 py-3 text-center text-sm font-semibold text-gray-800">₹ {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Summary --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50/80 to-white">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h8M9 7h12m-3-3l3 3-3 3"/>
                        </svg>
                        Order Summary
                    </h3>
                </div>
                <div class="p-6">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 text-sm text-gray-600">Products</td>
                                <td class="py-2.5 text-end font-semibold text-gray-800">{{ $purchaseOrder->items->count() }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 text-sm text-gray-600">Total Quantity</td>
                                <td class="py-2.5 text-end font-semibold text-gray-800">{{ $purchaseOrder->items->sum('quantity') }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 text-sm text-gray-600">Subtotal</td>
                                <td class="py-2.5 text-end font-medium text-gray-700">₹ {{ number_format($purchaseOrder->subtotal, 2) }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 text-sm text-gray-600">Tax</td>
                                <td class="py-2.5 text-end font-medium text-gray-700">₹ {{ number_format($purchaseOrder->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="pt-4 text-base font-bold text-gray-800">Grand Total</td>
                                <td class="pt-4 text-end text-2xl font-bold text-[#8B2452]">₹ {{ number_format($purchaseOrder->grand_total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Invoice --}}
            @if($purchaseOrder->invoice_file)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50/80 to-white">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            Invoice
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="w-14 h-14 mx-auto text-[#8B2452] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                        <a href="{{ App\Helpers\S3Helper::url($purchaseOrder->invoice_file) }}" target="_blank"
                           class="text-[#8B2452] font-semibold hover:underline inline-flex items-center gap-2">
                            View Uploaded Invoice
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                        <p class="text-xs text-gray-400 mt-2">{{ basename($purchaseOrder->invoice_file) }}</p>
                    </div>
                </div>
            @endif

            @if($purchaseOrder->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Notes
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 text-sm">{{ $purchaseOrder->notes }}</p>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection