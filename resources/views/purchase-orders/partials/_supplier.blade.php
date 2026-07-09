<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">

                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 9V7a5 5 0 00-10 0v2M5 9h14v10H5V9z"/>

                </svg>

            </div>

            <h3 class="text-base font-bold text-gray-800">

                Supplier Information

            </h3>

            <span class="ml-auto text-xs text-gray-400">

                Select supplier for purchase order

            </span>

        </div>

    </div>

    <div class="p-5">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">

                    Supplier

                    <span class="text-red-500">*</span>

                </label>

                <select

                    id="supplierSelect"

                    name="supplier_id"

                    class="w-full px-3 py-2 border border-gray-200 rounded-lg
                           focus:outline-none
                           focus:border-[#8B2452]
                           focus:ring-2
                           focus:ring-[#8B2452]/20">

                    <option value="">

                        Select Supplier

                    </option>

                    @foreach($suppliers as $supplier)

                        <option

                            value="{{ $supplier->id }}"

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

                            @if($supplier->company_name)

                                ({{ $supplier->company_name }})

                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div

            id="supplierDetails"

            class="{{ (old('supplier_id', $purchaseOrder->supplier_id ?? '') != '') ? '' : 'hidden' }} mt-6">

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">

                <div>

                    <p class="text-xs text-gray-500">

                        Contact Person

                    </p>

                    <p id="supplierName" class="font-semibold text-gray-800">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->name }}
                        @else
                            -
                        @endif

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Company

                    </p>

                    <p id="supplierCompany" class="font-semibold text-gray-800">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->company_name ?? '-' }}
                        @else
                            -
                        @endif

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Phone

                    </p>

                    <p id="supplierPhone" class="font-semibold text-gray-800">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->phone ?? '-' }}
                        @else
                            -
                        @endif

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Email

                    </p>

                    <p id="supplierEmail" class="font-semibold text-gray-800">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->email ?? '-' }}
                        @else
                            -
                        @endif

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        GST Number

                    </p>

                    <p id="supplierGST" class="font-semibold text-gray-800">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->gst_number ?? '-' }}
                        @else
                            -
                        @endif

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Payment Terms

                    </p>

                    <p id="supplierPayment" class="font-semibold text-[#8B2452]">

                        @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                            {{ $purchaseOrder->supplier->payment_terms ?? '-' }}
                        @else
                            -
                        @endif

                    </p>

                </div>

            </div>

            <div class="mt-6 border-t border-gray-200 pt-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">

                            Address

                        </p>

                        <p id="supplierAddress" class="text-sm text-gray-700">

                            @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                                {{ $purchaseOrder->supplier->address ?? '-' }}
                            @else
                                -
                            @endif

                        </p>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">

                            City / State / Country

                        </p>

                        <p id="supplierLocation" class="text-sm text-gray-700">

                            @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                                {{ $purchaseOrder->supplier->city ?? '' }}
                                {{ $purchaseOrder->supplier->state ?? '' }}
                                {{ $purchaseOrder->supplier->country ?? '' }}
                                {{ $purchaseOrder->supplier->pincode ?? '' }}
                            @else
                                -
                            @endif

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>