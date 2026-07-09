<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

        <div class="flex items-center gap-2">

            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">

                <svg class="w-4 h-4 text-[#8B2452]"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 17v-2a4 4 0 014-4h8M9 7h12m-3-3l3 3-3 3"/>

                </svg>

            </div>

            <h3 class="text-base font-bold text-gray-800">

                Purchase Order Summary

            </h3>

        </div>

    </div>

    <div class="p-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>

                <table class="w-full">

                    <tbody>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Purchase Order No.

                            </td>

                            <td class="text-end font-semibold">

                                {{ $poNumber ?? '' }}

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Purchase Date

                            </td>

                            <td class="text-end">

                                <span id="summaryPurchaseDate">

                                    @if(isset($purchaseOrder) && $purchaseOrder->purchase_date)
                                        {{ date('d M Y', strtotime($purchaseOrder->purchase_date)) }}
                                    @else
                                        {{ date('d M Y') }}
                                    @endif

                                </span>

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Supplier

                            </td>

                            <td id="summarySupplier" class="text-end">

                                @if(isset($purchaseOrder) && $purchaseOrder->supplier)
                                    {{ $purchaseOrder->supplier->name }}
                                @else
                                    -
                                @endif

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Warehouse

                            </td>

                            <td id="summaryWarehouse" class="text-end">

                                @if(isset($purchaseOrder) && $purchaseOrder->warehouse)
                                    {{ $purchaseOrder->warehouse->name }}
                                @else
                                    -
                                @endif

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <div>

                <table class="w-full">

                    <tbody>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Products

                            </td>

                            <td class="text-end">

                                <span id="summaryProducts">

                                    @if(isset($purchaseOrder) && $purchaseOrder->items)
                                        {{ $purchaseOrder->items->count() }}
                                    @else
                                        0
                                    @endif

                                </span>

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Quantity

                            </td>

                            <td class="text-end">

                                <span id="summaryQty">

                                    @if(isset($purchaseOrder) && $purchaseOrder->items)
                                        {{ $purchaseOrder->items->sum('quantity') }}
                                    @else
                                        0
                                    @endif

                                </span>

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Subtotal

                            </td>

                            <td class="text-end">

                                ₹ <span id="summarySubtotal">

                                    @if(isset($purchaseOrder) && $purchaseOrder->subtotal)
                                        {{ number_format($purchaseOrder->subtotal, 2) }}
                                    @else
                                        0.00
                                    @endif

                                </span>

                            </td>

                        </tr>

                        <tr>

                            <td class="py-2 text-gray-600">

                                Tax

                            </td>

                            <td class="text-end">

                                ₹ <span id="summaryTax">

                                    @if(isset($purchaseOrder) && $purchaseOrder->tax_amount)
                                        {{ number_format($purchaseOrder->tax_amount, 2) }}
                                    @else
                                        0.00
                                    @endif

                                </span>

                            </td>

                        </tr>

                        <tr class="border-t">

                            <td class="pt-4 text-lg font-bold">

                                Grand Total

                            </td>

                            <td class="pt-4 text-end text-xl font-bold text-[#8B2452]">

                                ₹

                                <span id="summaryGrandTotal">

                                    @if(isset($purchaseOrder) && $purchaseOrder->grand_total)
                                        {{ number_format($purchaseOrder->grand_total, 2) }}
                                    @else
                                        0.00
                                    @endif

                                </span>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <input type="hidden" name="subtotal" id="subtotalInput" value="{{ isset($purchaseOrder) ? $purchaseOrder->subtotal : 0 }}">

        <input type="hidden" name="tax_amount" id="taxInput" value="{{ isset($purchaseOrder) ? $purchaseOrder->tax_amount : 0 }}">

        <input type="hidden" name="grand_total" id="grandTotalInput" value="{{ isset($purchaseOrder) ? $purchaseOrder->grand_total : 0 }}">

        <div class="flex justify-end gap-3 mt-8">

            <a
                href="{{ admin_route('purchase-orders.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">

                Cancel

            </a>

            <button
                type="submit"
                class="px-6 py-2.5 rounded-lg bg-[#8B2452] text-white hover:bg-[#741d45]">

                Save Purchase Order

            </button>

        </div>

    </div>

</div>