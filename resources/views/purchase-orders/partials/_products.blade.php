<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">

    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">

        <div class="flex items-center justify-between">

            <h3 class="text-base font-bold text-gray-800">

                Purchase Products

            </h3>

            <button
                type="button"
                id="addProductRow"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm">

                + Add Product

            </button>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full border-collapse">

            <thead class="bg-gray-50">

            <tr>

                <th class="border px-3 py-2 text-left">
                    Product
                </th>

                <th class="border px-3 py-2 text-left">
                    Variant
                </th>

                <th class="border px-3 py-2 text-center">
                    Purchase Price
                </th>

                <th class="border px-3 py-2 text-center">
                    Quantity
                </th>

                <th class="border px-3 py-2 text-center">
                    Total
                </th>

                <th class="border px-3 py-2 text-center">
                    Action
                </th>

            </tr>

            </thead>

            <tbody id="purchaseItemsTable">

                @if(isset($purchaseOrder) && $purchaseOrder->items->count() > 0)
                    @foreach($purchaseOrder->items as $item)
                    <tr class="purchase-row">

                        <td class="border p-2">

                            <select
                                name="product_id[]"
                                class="w-full rounded border product-select">

                                <option value="">
                                    Select Product
                                </option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach

                            </select>

                        </td>

                        <td class="border p-2">

                            <select
                                name="product_variant_id[]"
                                class="w-full rounded border variant-select">

                                <option value="">
                                    Select Variant
                                </option>

                                @if($item->product && $item->product->variants)
                                    @foreach($item->product->variants as $variant)
                                        <option value="{{ $variant->id }}" {{ $item->product_variant_id == $variant->id ? 'selected' : '' }}>
                                            @if($variant->variant && $variant->value)
                                                {{ $variant->variant->name ?? '' }} : {{ $variant->value->value ?? '' }}
                                            @else
                                                {{ $variant->variant_name ?? '' }} : {{ $variant->variant_value ?? '' }}
                                            @endif
                                        </option>
                                    @endforeach
                                @endif

                            </select>

                        </td>

                        <td class="border p-2">

                            <input
                                type="number"
                                step="0.01"
                                name="purchase_price[]"
                                value="{{ $item->purchase_price }}"
                                class="w-full rounded border purchase-price">

                        </td>

                        <td class="border p-2">

                            <input
                                type="number"
                                min="1"
                                value="{{ $item->quantity }}"
                                name="quantity[]"
                                class="w-full rounded border quantity">

                        </td>

                        <td class="border p-2">

                            <input
                                type="text"
                                readonly
                                name="row_total[]"
                                value="{{ $item->total }}"
                                class="w-full rounded border bg-gray-100 row-total">

                        </td>

                        <td class="border p-2 text-center">

                            <button
                                type="button"
                                class="removeRow text-red-600">

                                Remove

                            </button>

                        </td>

                    </tr>
                    @endforeach
                @else
                    <tr class="purchase-row">

                        <td class="border p-2">

                            <select
                                name="product_id[]"
                                class="w-full rounded border product-select">

                                <option value="">
                                    Select Product
                                </option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach

                            </select>

                        </td>

                        <td class="border p-2">

                            <select
                                name="product_variant_id[]"
                                class="w-full rounded border variant-select">

                                <option value="">
                                    Select Variant
                                </option>

                            </select>

                        </td>

                        <td class="border p-2">

                            <input
                                type="number"
                                step="0.01"
                                name="purchase_price[]"
                                class="w-full rounded border purchase-price">

                        </td>

                        <td class="border p-2">

                            <input
                                type="number"
                                min="1"
                                value="1"
                                name="quantity[]"
                                class="w-full rounded border quantity">

                        </td>

                        <td class="border p-2">

                            <input
                                type="text"
                                readonly
                                name="row_total[]"
                                class="w-full rounded border bg-gray-100 row-total">

                        </td>

                        <td class="border p-2 text-center">

                            <button
                                type="button"
                                class="removeRow text-red-600">

                                Remove

                            </button>

                        </td>

                    </tr>
                @endif

            </tbody>

        </table>

    </div>

</div>

<template id="purchaseRowTemplate">

<tr class="purchase-row">

    <td class="border p-2">

        <select
            name="product_id[]"
            class="w-full rounded border product-select">

            <option value="">
                Select Product
            </option>

            @foreach($products as $product)
                <option value="{{ $product->id }}">
                    {{ $product->name }}
                </option>
            @endforeach

        </select>

    </td>

    <td class="border p-2">

        <select
            name="product_variant_id[]"
            class="w-full rounded border variant-select">

            <option value="">
                Select Variant
            </option>

        </select>

    </td>

    <td class="border p-2">

        <input
            type="number"
            step="0.01"
            min="0"
            name="purchase_price[]"
            class="w-full rounded border purchase-price">

    </td>

    <td class="border p-2">

        <input
            type="number"
            min="1"
            value="1"
            name="quantity[]"
            class="w-full rounded border quantity">

    </td>

    <td class="border p-2">

        <input
            type="text"
            readonly
            name="row_total[]"
            class="w-full rounded border bg-gray-100 row-total">

    </td>

    <td class="border p-2 text-center">

        <button
            type="button"
            class="removeRow text-red-600 font-semibold">

            Remove

        </button>

    </td>

</tr>

</template>