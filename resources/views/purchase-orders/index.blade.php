@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">

                Purchase Orders

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Manage supplier purchase orders

            </p>

        </div>

        <a
            href="{{ admin_route('purchase-orders.create') }}"
            class="px-5 py-2.5 rounded-lg bg-[#8B2452] text-white hover:bg-[#741d45]">

            + Create Purchase Order

        </a>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-5">

        <div class="p-5">

            <form
                method="GET"
                action="{{ admin_route('purchase-orders.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search PO Number / Invoice"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg">

                    </div>

                    <div>

                        <select
                            name="supplier_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg">

                            <option value="">

                                All Suppliers

                            </option>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    {{ request('supplier_id')==$supplier->id?'selected':'' }}>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <select
                            name="status"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg">

                            <option value="">

                                All Status

                            </option>

                            <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>

                            <option value="ordered" {{ request('status')=='ordered'?'selected':'' }}>Ordered</option>

                            <option value="received" {{ request('status')=='received'?'selected':'' }}>Received</option>

                            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>

                        </select>

                    </div>

                    <div>

                        <button
                            class="px-5 py-2.5 rounded-lg bg-[#8B2452] text-white">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left">PO Number</th>

                        <th class="px-4 py-3 text-left">Supplier</th>

                        <th class="px-4 py-3 text-left">Warehouse</th>

                        <th class="px-4 py-3 text-center">Invoice</th>

                        <th class="px-4 py-3 text-center">Amount</th>

                        <th class="px-4 py-3 text-center">Status</th>

                        <th class="px-4 py-3 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($purchaseOrders as $purchaseOrder)

                        <tr class="border-t">

                            <td class="px-4 py-4">

                                {{ $purchaseOrder->po_number }}

                            </td>

                            <td class="px-4 py-4">

                                {{ $purchaseOrder->supplier->name ?? '-' }}

                            </td>

                            <td class="px-4 py-4">

                                {{ $purchaseOrder->warehouse->name ?? '-' }}

                            </td>

                            <td class="px-4 py-4 text-center">

                                {{ $purchaseOrder->invoice_number ?: '-' }}

                            </td>

                            <td class="px-4 py-4 text-center">

                                ₹ {{ number_format($purchaseOrder->grand_total,2) }}

                            </td>

                            <td class="px-4 py-4 text-center">

                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">

                                    {{ ucfirst($purchaseOrder->status) }}

                                </span>

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ admin_route('purchase-orders.show',$purchaseOrder->id) }}"
                                        class="px-3 py-1 rounded bg-blue-100 text-blue-700">

                                        View

                                    </a>

                                    <a
                                        href="{{ admin_route('purchase-orders.edit',$purchaseOrder->id) }}"
                                        class="px-3 py-1 rounded bg-yellow-100 text-yellow-700">

                                        Edit

                                    </a>

                                    <form
                                        action="{{ admin_route('purchase-orders.destroy',$purchaseOrder->id) }}"
                                        method="POST">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Delete Purchase Order?')"
                                            class="px-3 py-1 rounded bg-red-100 text-red-700">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-10 text-gray-500">

                                No Purchase Orders Found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-5">

            {{ $purchaseOrders->links() }}

        </div>

    </div>

</div>

@endsection