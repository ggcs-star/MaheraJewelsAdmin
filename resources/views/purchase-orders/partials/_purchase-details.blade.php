<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">

                <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 012-2z"/>
                </svg>

            </div>

            <h3 class="text-base font-bold text-gray-800">

                Purchase Information

            </h3>

        </div>

    </div>

    <div class="p-5">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    PO Number

                </label>

                <input
                    type="text"
                    name="po_number"
                    value="{{ $poNumber ?? '' }}"
                    readonly
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 bg-gray-100">

            </div>

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    Invoice Number

                </label>

                <input
                    type="text"
                    name="invoice_number"
                    value="{{ old('invoice_number', $purchaseOrder->invoice_number ?? '') }}"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2">

            </div>

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    Purchase Date

                </label>

                <input
                    type="date"
                    name="purchase_date"
                    value="{{ old('purchase_date', isset($purchaseOrder) ? $purchaseOrder->purchase_date : date('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2"
                >

            </div>

            <div>

                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">

                    Payment Method

                </label>

                <select
                    name="payment_method"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2">

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