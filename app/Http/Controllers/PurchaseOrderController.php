<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with([
            'supplier',
            'warehouse'
        ])
        ->latest()
        ->paginate(15);

        $suppliers = Supplier::active()->get();

        return view(
            'purchase-orders.index',
            compact(
                'purchaseOrders',
                'suppliers'
            )
        );
    }

   public function create()
{
    $suppliers = Supplier::active()->get();
    $warehouses = Warehouse::all();
    $products = Product::with('variants')
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    // ✅ PO Number Auto Generate - Final Working Code
    $lastPO = PurchaseOrder::orderBy('id', 'desc')->first();
    
    if ($lastPO) {
        // PO-00001 se '00001' nikaalo aur integer mein convert karo
        $lastNumber = (int) substr($lastPO->po_number, 3);
        $number = $lastNumber + 1;
    } else {
        $number = 1;
    }
    
    $poNumber = 'PO-' . str_pad($number, 5, '0', STR_PAD_LEFT);

    // 🔍 Debug - Check karo log mein
    \Log::info('Create PO - Generated Number:', [
        'last_po' => $lastPO ? $lastPO->po_number : 'No PO',
        'last_number' => $lastPO ? (int) substr($lastPO->po_number, 3) : 0,
        'new_number' => $number,
        'po_number' => $poNumber
    ]);

    return view('purchase-orders.create', compact(
        'suppliers',
        'warehouses',
        'products',
        'poNumber'
    ));
}
   public function store(Request $request)
{
    try {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'payment_method' => 'required|string',
            'product_id' => 'required|array|min:1',
            'product_variant_id' => 'required|array|min:1',
            'purchase_price' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        $subtotal = 0;
        foreach ($request->purchase_price as $index => $price) {
            $qty = (int) $request->quantity[$index];
            $subtotal += floatval($price) * $qty;
        }
        
        $grandTotal = $subtotal;

        $invoiceFile = null;
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $originalName = $file->getClientOriginalName();
            $invoiceFile = $file->storeAs('purchase_orders', $originalName, 's3');
        }

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => $request->po_number,
            'supplier_id' => $request->supplier_id,
            'invoice_number' => $request->invoice_number ?? null,
            'purchase_date' => $request->purchase_date,
            'payment_method' => $request->payment_method,
            'invoice_file' => $invoiceFile,
            'subtotal' => $subtotal,
            'tax_amount' => 0,
            'grand_total' => $grandTotal,
            'notes' => $request->notes ?? null,
            'status' => 'draft',
        ]);

        foreach ($request->product_id as $index => $productId) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $productId,
                'product_variant_id' => $request->product_variant_id[$index],
                'purchase_price' => $request->purchase_price[$index],
                'quantity' => $request->quantity[$index],
                'total' => $request->purchase_price[$index] * $request->quantity[$index],
            ]);
             $variant = ProductVariant::find($request->product_variant_id[$index]);
            if ($variant) {
                $variant->quantity += $request->quantity[$index]; // PO create mein stock increase karo
                $variant->purchase_price = $request->purchase_price[$index];
                $variant->save();
            }
        
        }
        

        DB::commit();

        return redirect(admin_route('purchase-orders.index'))
            ->with('success', 'Purchase Order created successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()
            ->withErrors($e->errors())
            ->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('PO Error: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->with('error', 'Error: ' . $e->getMessage());
    }
}
public function show(PurchaseOrder $purchaseOrder)
{
    $purchaseOrder->load([

        'supplier',

        'warehouse',

        'items.product',

        'items.variant',
        

    ]);

    return view(
        'purchase-orders.show',
        compact('purchaseOrder')
    );
}

public function edit(PurchaseOrder $purchaseOrder)
{
    $purchaseOrder->load([
        'supplier',
        'warehouse',
        'items.product.variants',
        'items.variant'
    ]);

    $suppliers = Supplier::active()->get();
    $warehouses = Warehouse::all();
    $products = Product::with('variants')
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    $poNumber = $purchaseOrder->po_number;

    return view('purchase-orders.edit', compact(
        'purchaseOrder',
        'suppliers',
        'warehouses',
        'products',
        'poNumber'
    ));
}
public function update(Request $request, PurchaseOrder $purchaseOrder)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'invoice_number' => 'nullable|string|max:255',
        'purchase_date' => 'required|date',
        'payment_method' => 'required',
        'product_id' => 'required|array',
        'product_variant_id' => 'required|array',
        'purchase_price' => 'required|array',
        'quantity' => 'required|array',
    ]);

    DB::beginTransaction();

    try {
        // ✅ Pehle old stock wapas add karo
        foreach ($purchaseOrder->items as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->quantity += $item->quantity; // ✅ Add karo (minus nahi)
                $variant->save();
            }
        }

        // ✅ Invoice file handle karo
        if ($request->hasFile('invoice_file')) {
            if ($purchaseOrder->invoice_file) {
                Storage::disk('s3')->delete($purchaseOrder->invoice_file);
            }
            $file = $request->file('invoice_file');
            $originalName = $file->getClientOriginalName();
            $purchaseOrder->invoice_file = $file->storeAs('purchase_orders', $originalName, 's3');
        }

        // ✅ Subtotal calculate karo
        $subtotal = 0;
        foreach ($request->purchase_price as $index => $price) {
            $qty = (int) $request->quantity[$index];
            $subtotal += floatval($price) * $qty;
        }
        
        $grandTotal = $subtotal;

        // ✅ PO update karo
        $purchaseOrder->update([
            'supplier_id' => $request->supplier_id,
            'invoice_number' => $request->invoice_number,
            'purchase_date' => $request->purchase_date,
            'payment_method' => $request->payment_method,
            'invoice_file' => $purchaseOrder->invoice_file ?? null,
            'subtotal' => $subtotal,
            'tax_amount' => 0,
            'grand_total' => $grandTotal,
            'notes' => $request->notes,
        ]);

        // ✅ Old items delete karo
        $purchaseOrder->items()->delete();

        // ✅ Naye items create karo aur stock subtract karo
        foreach ($request->product_id as $index => $productId) {
            $qty = (int)$request->quantity[$index];
            $purchasePrice = (float)$request->purchase_price[$index];
            $total = $purchasePrice * $qty;

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $productId,
                'product_variant_id' => $request->product_variant_id[$index],
                'purchase_price' => $purchasePrice,
                'quantity' => $qty,
                'total' => $total,
            ]);

            // ✅ Stock subtract karo (kyunki naya PO hai)
            $variant = ProductVariant::find($request->product_variant_id[$index]);
            if ($variant) {
                $variant->quantity -= $qty;
                if ($variant->quantity < 0) {
                    $variant->quantity = 0;
                }
                $variant->purchase_price = $purchasePrice;
                $variant->save();
            }
        }

        DB::commit();

        return redirect(admin_route('purchase-orders.index'))
            ->with('success', 'Purchase Order Updated Successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
public function destroy(PurchaseOrder $purchaseOrder)
{
    DB::beginTransaction();

    try {
        foreach ($purchaseOrder->items as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                // ✅ PO delete ho rahi hai, toh stock wapas add karo
                $variant->quantity += $item->quantity;
                $variant->save();
            }
        }

        if ($purchaseOrder->invoice_file) {
            Storage::disk('s3')->delete($purchaseOrder->invoice_file);
        }

        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        DB::commit();

        return redirect(admin_route('purchase-orders.index'))
            ->with('success', 'Purchase Order Deleted Successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}

public function getSupplier(Supplier $supplier)
{
    return response()->json([

        'id' => $supplier->id,

        'name' => $supplier->name,

        'company_name' => $supplier->company_name,

        'email' => $supplier->email,

        'phone' => $supplier->phone,

        'address' => $supplier->address,

        'city' => $supplier->city,

        'state' => $supplier->state,

        'country' => $supplier->country,

        'pincode' => $supplier->pincode,

        'gst_number' => $supplier->gst_number,

        'payment_terms' => $supplier->payment_terms,

    ]);
}

public function getProductVariants(Product $product)
{
    $variants = $product->variants()
        ->with([
            'variant',
            'value'
        ])
        ->orderBy('id')
        ->get();

    return response()->json(

        $variants->map(function ($variant) {

            return [

                'id' => $variant->id,

                'variant_name' => optional($variant->variant)->name,

                'variant_value' => optional($variant->value)->value,

                'purchase_price' => $variant->purchase_price,

                'quantity' => $variant->quantity,

                'sku' => $variant->sku_suffix,

            ];

        })

    );
}
}