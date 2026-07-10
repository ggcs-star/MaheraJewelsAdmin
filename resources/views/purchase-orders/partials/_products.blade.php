<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50/80 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Purchase Products</h3>
                    <p class="text-xs text-gray-400">Add products to purchase order</p>
                </div>
            </div>
            <button type="button" id="addProductRow"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#8B2452] to-[#6B1A3E] text-white text-sm font-medium hover:shadow-lg hover:shadow-[#8B2452]/30 transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="border-b border-gray-200 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="border-b border-gray-200 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Variant</th>
                        <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Purchase Price</th>
                        <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="border-b border-gray-200 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="purchaseItemsTable">
                    @if(isset($purchaseOrder) && $purchaseOrder->items->count() > 0)
                        @foreach($purchaseOrder->items as $item)
                        <tr class="purchase-row hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="border-b border-gray-100 px-4 py-3">
                                <select name="product_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <select name="product_variant_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white">
                                    <option value="">Select Variant</option>
                                    @if($item->product && $item->product->variants)
                                        @foreach($item->product->variants as $variant)
                                            <option value="{{ $variant->id }}" {{ $item->product_variant_id == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->variant->name ?? '' }} : {{ $variant->value->value ?? '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="number" step="0.01" name="purchase_price[]" value="{{ $item->purchase_price }}"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center purchase-price">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="number" min="1" name="quantity[]" value="{{ $item->quantity }}"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center quantity">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="text" readonly name="row_total[]" value="{{ $item->total }}"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-center font-semibold row-total">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3 text-center">
                                <button type="button" class="removeRow text-red-500 hover:text-red-700 font-medium text-sm hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors duration-150">
                                    Remove
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="purchase-row hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="border-b border-gray-100 px-4 py-3">
                                <select name="product_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white product-select">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <select name="product_variant_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white variant-select">
                                    <option value="">Select Variant</option>
                                </select>
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="number" step="0.01" name="purchase_price[]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center purchase-price">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="number" min="1" value="1" name="quantity[]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center quantity">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3">
                                <input type="text" readonly name="row_total[]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-center font-semibold row-total">
                            </td>
                            <td class="border-b border-gray-100 px-4 py-3 text-center">
                                <button type="button" class="removeRow text-red-500 hover:text-red-700 font-medium text-sm hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors duration-150">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<template id="purchaseRowTemplate">
    <tr class="purchase-row hover:bg-gray-50/50 transition-colors duration-150">
        <td class="border-b border-gray-100 px-4 py-3">
            <select name="product_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white product-select">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="border-b border-gray-100 px-4 py-3">
            <select name="product_variant_id[]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white variant-select">
                <option value="">Select Variant</option>
            </select>
        </td>
        <td class="border-b border-gray-100 px-4 py-3">
            <input type="number" step="0.01" min="0" name="purchase_price[]"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center purchase-price">
        </td>
        <td class="border-b border-gray-100 px-4 py-3">
            <input type="number" min="1" value="1" name="quantity[]"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 text-center quantity">
        </td>
        <td class="border-b border-gray-100 px-4 py-3">
            <input type="text" readonly name="row_total[]"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-center font-semibold row-total">
        </td>
        <td class="border-b border-gray-100 px-4 py-3 text-center">
            <button type="button" class="removeRow text-red-500 hover:text-red-700 font-medium text-sm hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors duration-150">
                Remove
            </button>
        </td>
    </tr>
</template>