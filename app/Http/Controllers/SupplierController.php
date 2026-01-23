<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
   
  public function index(Request $request)
{
    $suppliers = Supplier::query()
        ->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        })
        ->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }) ->latest()->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }


    public function create()
    {
        return view('suppliers.create');
    }


    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        Supplier::create($data);

        return redirect()
            ->to(admin_route('suppliers.index'))
            ->with('success', 'Supplier created successfully.');
    }


    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }


    public function update(Request $request, Supplier $supplier)
    {
        $data = $this->validatedData($request, $supplier->id);

        $supplier->update($data);

        return redirect()
            ->to(admin_route('suppliers.index'))
            ->with('success', 'Supplier updated successfully.');
    }


    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return back()->with('success', 'Supplier deleted successfully.');
    }


    private function validatedData(Request $request, $supplierId = null): array
    {
        return $request->validate([
            'type' => 'required|in:manufacturer,distributor',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',

            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplierId,

            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',

            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',

            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',

            'payment_terms' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);
    }
}
