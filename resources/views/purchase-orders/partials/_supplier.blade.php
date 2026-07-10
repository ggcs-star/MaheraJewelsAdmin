<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50/80 to-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#8B2452]/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2M5 9h14v10H5V9z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">Supplier Information</h3>
                <p class="text-xs text-gray-400">Select supplier for purchase order</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Supplier <span class="text-red-500">*</span>
                </label>
                <select id="supplierSelect" name="supplier_id"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white text-gray-700">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            data-name="{{ $supplier->name }}"
                            data-company="{{ $supplier->company_name }}"
                            data-phone="{{ $supplier->phone }}"
                            data-email="{{ $supplier->email }}"
                            data-gst="{{ $supplier->gst_number }}"
                            data-address="{{ $supplier->address }}"
                            data-city="{{ $supplier->city }}"
                            data-state="{{ $supplier->state }}"
                            data-country="{{ $supplier->country }}"
                            data-pincode="{{ $supplier->pincode }}"
                            data-payment="{{ $supplier->payment_terms }}"
                            {{ (old('supplier_id', $purchaseOrder->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                            {{ $supplier->name }}
                            @if($supplier->company_name) ({{ $supplier->company_name }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="supplierDetails" class="{{ (old('supplier_id', $purchaseOrder->supplier_id ?? '') != '') ? '' : 'hidden' }} mt-6">
            <div class="bg-gray-50/80 rounded-xl p-5 border border-gray-100">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Contact</p>
                        <p id="supplierName" class="font-semibold text-gray-800 text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->name }} @else - @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Company</p>
                        <p id="supplierCompany" class="font-semibold text-gray-800 text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->company_name ?? '-' }} @else - @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Phone</p>
                        <p id="supplierPhone" class="font-semibold text-gray-800 text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->phone ?? '-' }} @else - @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Email</p>
                        <p id="supplierEmail" class="font-semibold text-gray-800 text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->email ?? '-' }} @else - @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">GST</p>
                        <p id="supplierGST" class="font-semibold text-gray-800 text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->gst_number ?? '-' }} @else - @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Payment Terms</p>
                        <p id="supplierPayment" class="font-semibold text-[#8B2452] text-sm mt-1">
                            @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->payment_terms ?? '-' }} @else - @endif
                        </p>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Address</p>
                            <p id="supplierAddress" class="text-sm text-gray-700 mt-1">
                                @if(isset($purchaseOrder) && $purchaseOrder->supplier) {{ $purchaseOrder->supplier->address ?? '-' }} @else - @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Location</p>
                            <p id="supplierLocation" class="text-sm text-gray-700 mt-1">
                                @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                                    {{ $purchaseOrder->supplier->city ?? '' }} {{ $purchaseOrder->supplier->state ?? '' }} {{ $purchaseOrder->supplier->country ?? '' }} {{ $purchaseOrder->supplier->pincode ?? '' }}
                                @else - @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>