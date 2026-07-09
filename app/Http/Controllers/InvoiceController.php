<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::where('organization_id', activeOrganization()->id)
    ->orderByDesc('invoice_date');

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        $invoices = $query->paginate(10)->withQueryString();
        $totalAmount = (clone $query)->sum('grand_total');

        return view('invoices.index', compact('invoices', 'totalAmount'));
    }
    public function create()
    {
        $customers = Customer::where('organization_id', activeOrganization()->id)
    ->orderBy('name')
    ->get();

        $products  = Product::orderBy('name')->get();

        return view('invoices.create', compact('customers', 'products'));
    }
    public function productVariants(Product $product)
    {
        return response()->json(
            $product->variants()
                ->where('status', 1)
                ->get(['id', 'sku_suffix', 'selling_price'])
        );
    }
public function store(Request $request)
{
    $org = activeOrganization();

    $request->validate([
        'customer_id'  => 'nullable|exists:customers,id',
        'payment_type' => 'required|in:cash,bank',
        'paid_amount'  => 'nullable|numeric|min:0',

        'items'              => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.variant_id' => 'required|exists:product_variants,id',
        'items.*.qty'        => 'required|integer|min:1',
        'items.*.price'      => 'required|numeric|min:0',
        'items.*.discount'   => 'nullable|numeric|min:0',
        'items.*.discount_type' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request, $org) {

        $customerId = $customerName = $customerMobile = $customerAddress = null;

        if ($request->filled('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
            $customerId      = $customer->id;
            $customerName    = $customer->name;
            $customerMobile  = $customer->mobile;
            $customerAddress = $customer->address_line_1;
        }

        $subTotal = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        foreach ($request->items as $item) {

            $price = (float) $item['price'];
            $subTotal += $price;

            $discountValue = (float) ($item['discount'] ?? 0);

            $discountType = in_array($item['discount_type'] ?? '', ['percent','flat'])
                ? $item['discount_type']
                : 'percent';

            $discountAmount = $discountType === 'percent'
                ? ($price * $discountValue) / 100
                : $discountValue;

            $discountAmount = min($discountAmount, $price);

            $totalDiscount += $discountAmount;
            $grandTotal    += ($price - $discountAmount);
        }

        $paid = (float) ($request->paid_amount ?? 0);
        $due  = max($grandTotal - $paid, 0);
        $invoice = Invoice::create([
            'organization_id' => $org->id,
            'invoice_number'  => 'INV-' . str_pad((Invoice::max('id') + 1), 5, '0', STR_PAD_LEFT),
            'invoice_date'    => now(),

            'customer_id'      => $customerId,
            'customer_name'    => $customerName,
            'customer_mobile'  => $customerMobile,
            'customer_address' => $customerAddress,

            'payment_type' => $request->payment_type,
            'paid_amount'  => $paid,
            'due_amount'   => $due,

            'sub_total'   => $subTotal,
            'discount'    => $totalDiscount,
            'grand_total' => $grandTotal,
            'status'      => 'completed',
        ]);

        foreach ($request->items as $item) {

            $product = Product::findOrFail($item['product_id']);
            $variant = ProductVariant::findOrFail($item['variant_id']);

            $price = (float) $item['price'];
            $discountValue = (float) ($item['discount'] ?? 0);

            $discountType = in_array($item['discount_type'] ?? '', ['percent','flat'])
                ? $item['discount_type']
                : 'percent';

            $discountAmount = $discountType === 'percent'
                ? ($price * $discountValue) / 100
                : $discountValue;

            $discountAmount = min($discountAmount, $price);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,

                'product_name' => $product->name,
                'variant_name' => $variant->sku_suffix,

                'quantity' => $item['qty'],
                'price'    => $price,
                'discount' => $discountValue,
                'discount_type' => $discountType,
                'total'    => $price - $discountAmount,
            ]);
            $variant->decrement('quantity', $item['qty']);
        }
    });

    return redirect()->route('admin.invoices.index')
        ->with('success', 'Invoice created successfully');
}
    public function show(Invoice $invoice)
{
    $invoice->load(['items','organization']);
    return view('invoices.show', compact('invoice'));
}
public function edit(Invoice $invoice)
{
    $invoice->load('items');
    $customers = Customer::where('organization_id', activeOrganization()->id)->get();
    $products = Product::all();

    return view('invoices.edit', compact('invoice','customers','products'));
}
public function update(Request $request, Invoice $invoice)
{
    $request->validate([
        'customer_id'  => 'nullable|exists:customers,id',
        'payment_type' => 'required|in:cash,bank',
        'paid_amount'  => 'nullable|numeric|min:0',

        'items'              => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.variant_id' => 'required|exists:product_variants,id',
        'items.*.qty'        => 'required|integer|min:1',
        'items.*.price'      => 'required|numeric|min:0',
        'items.*.discount'   => 'nullable|numeric|min:0',
        'items.*.discount_type' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request, $invoice) {

        $customerId = $customerName = $customerMobile = $customerAddress = null;

        if ($request->filled('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
            $customerId      = $customer->id;
            $customerName    = $customer->name;
            $customerMobile  = $customer->mobile;
            $customerAddress = $customer->address_line_1;
        }

        $subTotal = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        foreach ($request->items as $item) {

            $price = (float) $item['price'];
            $subTotal += $price;

            $discountValue = (float) ($item['discount'] ?? 0);

            $discountType = in_array($item['discount_type'] ?? '', ['percent','flat'])
                ? $item['discount_type']
                : 'percent';

            $discountAmount = $discountType === 'percent'
                ? ($price * $discountValue) / 100
                : $discountValue;

            $discountAmount = min($discountAmount, $price);

            $totalDiscount += $discountAmount;
            $grandTotal    += ($price - $discountAmount);
        }

        $paid = (float) ($request->paid_amount ?? 0);
        $due  = max($grandTotal - $paid, 0);

        $invoice->update([
            'customer_id'      => $customerId,
            'customer_name'    => $customerName,
            'customer_mobile'  => $customerMobile,
            'customer_address' => $customerAddress,

            'payment_type' => $request->payment_type,
            'paid_amount'  => $paid,
            'due_amount'   => $due,

            'sub_total'   => $subTotal,
            'discount'    => $totalDiscount,
            'grand_total' => $grandTotal,
        ]);

        $invoice->items()->delete();

        foreach ($request->items as $item) {

            $product = Product::findOrFail($item['product_id']);
            $variant = ProductVariant::findOrFail($item['variant_id']);

            $price = (float) $item['price'];
            $discountValue = (float) ($item['discount'] ?? 0);

            $discountType = in_array($item['discount_type'] ?? '', ['percent','flat'])
                ? $item['discount_type']
                : 'percent';

            $discountAmount = $discountType === 'percent'
                ? ($price * $discountValue) / 100
                : $discountValue;

            $discountAmount = min($discountAmount, $price);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,

                'product_name' => $product->name,
                'variant_name' => $variant->sku_suffix,

                'quantity' => $item['qty'],
                'price'    => $price,
                'discount' => $discountValue,
                'discount_type' => $discountType,
                'total'    => $price - $discountAmount,
            ]);
        }
    });

    return redirect()->route('admin.invoices.index')
        ->with('success', 'Invoice updated successfully');
}
public function destroy(Invoice $invoice)
{
    $invoice->items()->delete(); 
    $invoice->delete();

    return redirect()
        ->route('admin.invoices.index')
        ->with('success', 'Invoice deleted successfully');
}

}
