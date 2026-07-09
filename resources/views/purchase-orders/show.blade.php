@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">

                Purchase Order Details

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                View purchase order information

            </p>

        </div>

        <div class="flex gap-3">

            <a

                href="{{ admin_route('purchase-orders.edit', $purchaseOrder->id) }}"

                class="px-5 py-2.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">

                Edit

            </a>

            <a

                href="{{ admin_route('purchase-orders.index') }}"

                class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">

                Back

            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

            {{ session('error') }}

        </div>

    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                    <h3 class="text-base font-bold text-gray-800">PO Information</h3>

                </div>

                <div class="p-5">

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">PO Number</p>

                            <p class="font-semibold text-gray-800">{{ $purchaseOrder->po_number }}</p>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">Invoice Number</p>

                            <p class="font-semibold text-gray-800">{{ $purchaseOrder->invoice_number ?: '-' }}</p>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">Purchase Date</p>

                            <p class="font-semibold text-gray-800">{{ date('d M Y', strtotime($purchaseOrder->purchase_date)) }}</p>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</p>

                            <p class="font-semibold text-gray-800">{{ $purchaseOrder->payment_method ?: '-' }}</p>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>

                            <span class="px-3 py-1 rounded-full text-xs {{ $purchaseOrder->status == 'draft' ? 'bg-gray-100 text-gray-700' : ($purchaseOrder->status == 'ordered' ? 'bg-blue-100 text-blue-700' : ($purchaseOrder->status == 'received' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">

                                {{ ucfirst($purchaseOrder->status) }}

                            </span>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wider">Total Amount</p>

                            <p class="font-bold text-[#8B2452]">₹ {{ number_format($purchaseOrder->grand_total, 2) }}</p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                    <h3 class="text-base font-bold text-gray-800">Supplier Information</h3>

                </div>

                <div class="p-5">

                    @if($purchaseOrder->supplier)

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Name</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->name }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Company</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->company_name ?? '-' }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->phone ?? '-' }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->email ?? '-' }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">GST Number</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->gst_number ?? '-' }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Terms</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->payment_terms ?? '-' }}</p>

                            </div>

                            <div class="col-span-2">

                                <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>

                                <p class="font-semibold text-gray-800">{{ $purchaseOrder->supplier->address ?? '-' }}</p>

                            </div>

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wider">City / State</p>

                                <p class="font-semibold text-gray-800">

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

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                    <div class="flex items-center justify-between">

                        <h3 class="text-base font-bold text-gray-800">Purchase Products</h3>

                        <span class="text-sm text-gray-500">Total Items: {{ $purchaseOrder->items->count() }}</span>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full border-collapse">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="border px-3 py-2 text-left">Product</th>

                                <th class="border px-3 py-2 text-left">Variant</th>

                                <th class="border px-3 py-2 text-center">Purchase Price</th>

                                <th class="border px-3 py-2 text-center">Quantity</th>

                                <th class="border px-3 py-2 text-center">Total</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($purchaseOrder->items as $item)

                                <tr>

                                    <td class="border px-3 py-2">{{ $item->product->name ?? '-' }}</td>

                                    <td class="border px-3 py-2">
                                        @if($item->variant)
                                            @php
                                                $variantName = '';
                                                if($item->variant->variant) {
                                                    $variantName = $item->variant->variant->name ?? '';
                                                }
                                                if($item->variant->value) {
                                                    $variantName .= ' : ' . ($item->variant->value->value ?? '');
                                                }
                                                if(empty($variantName)) {
                                                    $variantName = $item->variant->variant_name ?? $item->variant->id;
                                                }
                                            @endphp
                                            {{ $variantName }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="border px-3 py-2 text-center">₹ {{ number_format($item->purchase_price, 2) }}</td>

                                    <td class="border px-3 py-2 text-center">{{ $item->quantity }}</td>

                                    <td class="border px-3 py-2 text-center font-semibold">₹ {{ number_format($item->total, 2) }}</td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-4 text-gray-500">No products found</td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                    <h3 class="text-base font-bold text-gray-800">Order Summary</h3>

                </div>

                <div class="p-5">

                    <table class="w-full">

                        <tbody>

                            <tr>

                                <td class="py-2 text-gray-600">Products</td>

                                <td class="text-end font-semibold">{{ $purchaseOrder->items->count() }}</td>

                            </tr>

                            <tr>

                                <td class="py-2 text-gray-600">Total Quantity</td>

                                <td class="text-end font-semibold">{{ $purchaseOrder->items->sum('quantity') }}</td>

                            </tr>

                            <tr>

                                <td class="py-2 text-gray-600">Subtotal</td>

                                <td class="text-end">₹ {{ number_format($purchaseOrder->subtotal, 2) }}</td>

                            </tr>

                            <tr>

                                <td class="py-2 text-gray-600">Tax</td>

                                <td class="text-end">₹ {{ number_format($purchaseOrder->tax_amount, 2) }}</td>

                            </tr>

                            <tr class="border-t">

                                <td class="pt-4 text-lg font-bold">Grand Total</td>

                                <td class="pt-4 text-end text-xl font-bold text-[#8B2452]">₹ {{ number_format($purchaseOrder->grand_total, 2) }}</td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            @if($purchaseOrder->invoice_file)

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                        <h3 class="text-base font-bold text-gray-800">Invoice</h3>

                    </div>

                    <div class="p-5 text-center">

                        <svg class="w-12 h-12 mx-auto text-[#8B2452] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>

                        </svg>

                     <a href="{{ Storage::disk('s3')->url($purchaseOrder->invoice_file) }}" target="_blank">
    View Uploaded Invoice
</a>

                        <p class="text-xs text-gray-400 mt-1">{{ basename($purchaseOrder->invoice_file) }}</p>

                    </div>

                </div>

            @endif

            @if($purchaseOrder->notes)

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

                        <h3 class="text-base font-bold text-gray-800">Notes</h3>

                    </div>

                    <div class="p-5">

                        <p class="text-gray-700">{{ $purchaseOrder->notes }}</p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection