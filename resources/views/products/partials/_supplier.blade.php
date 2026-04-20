@php
    /** @var \App\Models\Product|null $product */
@endphp
@php
    $product = $product ?? null;
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Supplier & Procurement</h3>
            <span class="ml-auto text-xs text-gray-400">Manage supplier and delivery details</span>
        </div>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Select Supplier <span class="text-red-500">*</span>
                    </label>
                    <select id="supplierSelect"
                            name="supplier_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                                data-name="{{ $sup->name }}"
                                data-company="{{ $sup->company_name }}"
                                data-phone="{{ $sup->phone }}"
                                data-email="{{ $sup->email }}"
                                data-type="{{ ucfirst($sup->type) }}"
                                data-commission="{{ $sup->commission_type }} ({{ $sup->commission_value }})"
                                {{ old('supplier_id', $product->supplier_id ?? '') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                                @if($sup->company_name)
                                    ({{ $sup->company_name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Optional – used for purchase & commission calculation.</p>
                </div>
            </div>

            <div id="supplierBox" class="{{ old('supplier_id', $product->supplier_id ?? false) ? '' : 'd-none' }}">
                <div class="bg-gray-50/50 rounded-lg border border-gray-100 p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Supplier Details</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400">Name</p>
                            <p id="sName" class="text-sm font-medium text-gray-800">—</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Company</p>
                            <p id="sCompany" class="text-sm font-medium text-gray-800">—</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p id="sPhone" class="text-sm font-medium text-gray-800">—</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p id="sEmail" class="text-sm font-medium text-gray-800">—</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Type</p>
                            <p id="sType" class="text-sm font-medium text-gray-800">—</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Commission</p>
                            <p id="sCommission" class="text-sm font-bold text-[#8B2452]">—</p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">

                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Procurement Details</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                Warehouse (City)
                            </label>
                            <select name="warehouse_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                                <option value="">Select warehouse</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ old('warehouse_id', $product->warehouse_id ?? '') == $wh->id ? 'selected' : '' }}>
                                        {{ $wh->city }} — {{ $wh->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                Expected Delivery Date
                            </label>
                            <input type="date"
                                   name="expected_delivery_date"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                                   value="{{ old('expected_delivery_date', optional($product)->expected_delivery_date ? \Carbon\Carbon::parse($product->expected_delivery_date)->format('Y-m-d') : '') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                Payment Terms
                            </label>
                            @php
                                $savedTerms = old('payment_terms', $product->payment_terms ?? '');
                                $isCustom = $savedTerms && !in_array($savedTerms, ['advance','net_7','net_15','net_30']);
                                $customDays = $isCustom ? str_replace('net_', '', $savedTerms) : '';
                            @endphp
                            <select id="paymentTermsSelect" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                                <option value="">Select terms</option>
                                <option value="advance" {{ $savedTerms === 'advance' ? 'selected' : '' }}>Advance</option>
                                <option value="net_7" {{ $savedTerms === 'net_7' ? 'selected' : '' }}>7 Days</option>
                                <option value="net_15" {{ $savedTerms === 'net_15' ? 'selected' : '' }}>15 Days</option>
                                <option value="net_30" {{ $savedTerms === 'net_30' ? 'selected' : '' }}>30 Days</option>
                                <option value="custom" {{ $isCustom ? 'selected' : '' }}>Custom (Days)</option>
                            </select>
                            <input type="number"
                                   id="customPaymentDays"
                                   class="w-full mt-2 px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm {{ $isCustom ? '' : 'd-none' }}"
                                   placeholder="Enter days (e.g. 60, 90)"
                                   min="1"
                                   value="{{ $customDays }}">
                            <input type="hidden"
                                   name="payment_terms"
                                   id="finalPaymentTerms"
                                   value="{{ $savedTerms }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>