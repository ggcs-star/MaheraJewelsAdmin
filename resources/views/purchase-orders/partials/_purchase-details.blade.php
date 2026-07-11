<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">Purchase Information</h3>
                <p class="text-xs text-gray-400">Enter purchase order details</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">PO Number</label>
                <input type="text" name="po_number"
                    value="{{ $poNumber ?? '' }}"
                    readonly
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Invoice Number</label>
                <input type="text" name="invoice_number"
                    value="{{ old('invoice_number', $purchaseOrder->invoice_number ?? '') }}"
                    placeholder="Enter invoice number"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Purchase Date</label>
                <input type="date" name="purchase_date"
                    value="{{ old('purchase_date', isset($purchaseOrder) ? $purchaseOrder->purchase_date : date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Payment Method</label>
                <select name="payment_method"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all duration-200 bg-white">
                    <option value="">Select Payment</option>
                    <option value="Cash" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Cash') ? 'selected' : '' }}>Cash</option>
                    <option value="UPI" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'UPI') ? 'selected' : '' }}>UPI</option>
                    <option value="Card" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Card') ? 'selected' : '' }}>Card</option>
                    <option value="Bank Transfer" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Bank Transfer') ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Cheque') ? 'selected' : '' }}>Cheque</option>
                    <option value="Net Banking" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Net Banking') ? 'selected' : '' }}>Net Banking</option>
                    <option value="Credit" {{ (old('payment_method', $purchaseOrder->payment_method ?? '') == 'Credit') ? 'selected' : '' }}>Credit</option>
                </select>
            </div>
        </div>
    </div>
</div>