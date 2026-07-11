<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\PurchaseOrderItem;
use App\Models\PlatformPricing;
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

    $poProductIds = PurchaseOrderItem::whereHas('purchaseOrder', function($query) {
        $query->where('status', '!=', 'cancelled');
    })->pluck('product_id')->unique()->toArray();

    $products = Product::whereIn('id', $poProductIds)
        ->orderBy('name')
        ->get();

    $poData = [];
    $purchaseItems = PurchaseOrderItem::with('variant', 'purchaseOrder')
        ->whereHas('purchaseOrder', function($query) {
            $query->where('status', '!=', 'cancelled');
        })
        ->get();

    foreach ($purchaseItems as $item) {
        if ($item->product_variant_id) {
            $poData[$item->product_variant_id] = [
                'quantity' => $item->quantity,
                'purchase_price' => $item->purchase_price,
                'po_number' => $item->purchaseOrder->po_number ?? 'N/A',
            ];
        }
    }

    $pushedQuantities = [];
    $pushedData = PlatformPricing::with('platformProduct')
        ->whereHas('platformProduct', function($q) {
            $q->where('platform_id', 3);
        })
        ->get();

    foreach ($pushedData as $pricing) {
        $variantId = $pricing->product_variant_id;
        $pushedQuantities[$variantId] = ($pushedQuantities[$variantId] ?? 0) + $pricing->quantity;
    }

    // ✅ Offline Pricing Data
    $offlinePricing = [];
    $offlineData = PlatformPricing::with('platformProduct')
        ->whereHas('platformProduct', function($q) {
            $q->where('platform_id', 4);
        })
        ->get();

    foreach ($offlineData as $pricing) {
        $variantId = $pricing->product_variant_id;
        $offlinePricing[$variantId] = [
            'price' => $pricing->price,
            'final_price' => $pricing->final_price,
            'discount_value' => $pricing->discount_value,
            'discount_type' => $pricing->discount_type,
            'quantity' => $pricing->quantity,
        ];
    }

    return view('invoices.create', compact('customers', 'products', 'poData', 'pushedQuantities', 'offlinePricing'));
}

    public function productVariants(Product $product)
{
    $poVariantIds = PurchaseOrderItem::where('product_id', $product->id)
        ->whereHas('purchaseOrder', function($query) {
            $query->where('status', '!=', 'cancelled');
        })
        ->pluck('product_variant_id')
        ->unique()
        ->toArray();

    $variants = $product->variants()
        ->whereIn('id', $poVariantIds)
        ->where('status', 1)
        ->get(['id', 'sku_suffix', 'selling_price']);

    $offlinePricing = [];
    $offlineData = PlatformPricing::whereHas('platformProduct', function($q) {
        $q->where('platform_id', 4);
    })->get();

    foreach ($offlineData as $pricing) {
        $offlinePricing[$pricing->product_variant_id] = $pricing;
    }

    $pushedQuantities = [];
    $pushedData = PlatformPricing::whereHas('platformProduct', function($q) {
        $q->where('platform_id', 4);
    })->get();

    foreach ($pushedData as $pricing) {
        $variantId = $pricing->product_variant_id;
        $pushedQuantities[$variantId] = ($pushedQuantities[$variantId] ?? 0) + $pricing->quantity;
    }

    return response()->json(
        $variants->map(function ($variant) use ($offlinePricing, $pushedQuantities) {
            $offline = $offlinePricing[$variant->id] ?? null;
            $pushedQty = $pushedQuantities[$variant->id] ?? 0;

            return [
                'id' => $variant->id,
                'sku_suffix' => $variant->sku_suffix,
                'selling_price' => $variant->selling_price,
                'offline_price' => $offline ? (float) $offline->price : 0,
                'offline_discount' => $offline ? (float) $offline->discount_value : 0,
                'offline_discount_type' => $offline ? $offline->discount_type : 'percentage',
                'offline_quantity' => $pushedQty,
            ];
        })
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

        foreach ($request->items as $item) {
            $variant = ProductVariant::findOrFail($item['variant_id']);
            
            $poItem = PurchaseOrderItem::where('product_variant_id', $variant->id)
                ->whereHas('purchaseOrder', function($q) {
                    $q->where('status', '!=', 'cancelled');
                })
                ->first();
            
            $poQuantity = $poItem ? $poItem->quantity : 0;
            
            $pushedQty = PlatformPricing::where('product_variant_id', $variant->id)->sum('quantity');
            
            $availableQty = $poQuantity - $pushedQty;
            
            if ($item['qty'] > $availableQty) {
                throw new \Exception("Not enough stock. Available: {$availableQty}, Requested: {$item['qty']}");
            }
        }

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

            // ✅ Offline PlatformPricing update
            $offlinePricing = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })
                ->first();

            if ($offlinePricing) {
                $newQty = $offlinePricing->quantity - $item['qty'];
                if ($newQty < 0) $newQty = 0;
                $offlinePricing->update(['quantity' => $newQty]);
            }
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

        foreach ($invoice->items as $oldItem) {
            $variant = ProductVariant::find($oldItem->product_variant_id);
            if ($variant) {
                $variant->increment('quantity', $oldItem->quantity);
            }

            $offlinePricing = PlatformPricing::where('product_variant_id', $oldItem->product_variant_id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })
                ->first();

            if ($offlinePricing) {
                $offlinePricing->increment('quantity', $oldItem->quantity);
                $offlinePricing->status = 'active';
                $offlinePricing->save();
            }
        }

        foreach ($request->items as $item) {
            $variant = ProductVariant::findOrFail($item['variant_id']);
            
            $poItem = PurchaseOrderItem::where('product_variant_id', $variant->id)
                ->whereHas('purchaseOrder', function($q) {
                    $q->where('status', '!=', 'cancelled');
                })
                ->first();
            
            $poQuantity = $poItem ? $poItem->quantity : 0;
            
            $pushedQty = PlatformPricing::where('product_variant_id', $variant->id)->sum('quantity');
            
            $availableQty = $poQuantity - $pushedQty;
            
            if ($item['qty'] > $availableQty) {
                throw new \Exception("Not enough stock. Available: {$availableQty}, Requested: {$item['qty']}");
            }
        }

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

            $variant->decrement('quantity', $item['qty']);

            $offlinePricing = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })
                ->first();

            if ($offlinePricing) {
                $offlinePricing->decrement('quantity', $item['qty']);
                if ($offlinePricing->quantity <= 0) {
                    $offlinePricing->quantity = 0;
                    $offlinePricing->status = 'inactive';
                }
                $offlinePricing->save();
            }
        }
    });

    return redirect()->route('admin.invoices.index')
        ->with('success', 'Invoice updated successfully');
}

public function destroy(Invoice $invoice)
{
    DB::transaction(function () use ($invoice) {

        foreach ($invoice->items as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->increment('quantity', $item->quantity);
            }

            $offlinePricing = PlatformPricing::where('product_variant_id', $item->product_variant_id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })
                ->first();

            if ($offlinePricing) {
                $offlinePricing->increment('quantity', $item->quantity);
                $offlinePricing->status = 'active';
                $offlinePricing->save();
            }
        }

        $invoice->items()->delete();
        $invoice->delete();
    });

    return redirect()->route('admin.invoices.index')
        ->with('success', 'Invoice deleted successfully');
}
}