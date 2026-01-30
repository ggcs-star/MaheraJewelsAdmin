<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    
 public function index(Request $request)
{
    $warehouses = Warehouse::query()

        ->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('code', 'like', "%{$search}%")
                   ->orWhere('manager_name', 'like', "%{$search}%");
            });
        })

        ->when($request->filled('city'), function ($q) use ($request) {
            $q->where('city', $request->city);
        })

        ->when(
            $request->filled(['adv_field', 'adv_condition', 'adv_value']),
            function ($q) use ($request) {

                $allowedFields = [
                    'name',
                    'code',
                    'city',
                    'manager_name',
                    'status',
                ];

                $field = $request->adv_field;
                $condition = $request->adv_condition;
                $value = $request->adv_value;

                if (!in_array($field, $allowedFields)) {
                    return;
                }

                switch ($condition) {
                    case 'like':
                        $q->where($field, 'LIKE', "%{$value}%");
                        break;

                    case 'starts_with':
                        $q->where($field, 'LIKE', "{$value}%");
                        break;

                    case 'ends_with':
                        $q->where($field, 'LIKE', "%{$value}");
                        break;

                    case '!=':
                        $q->where($field, '!=', $value);
                        break;

                    default: 
                        $q->where($field, $value);
                }
            }
        )

        ->latest()
        ->paginate(10);

    // 🌍 CITIES
    $cities = Warehouse::whereNotNull('city')
        ->distinct()
        ->pluck('city');

    return view('warehouses.index', compact('warehouses', 'cities'));
}

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        Warehouse::create($data);

        return redirect()
            ->to(admin_route('warehouses.index'))
            ->with('success', 'Warehouse created successfully.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $this->validatedData($request, $warehouse->id);

        $warehouse->update($data);

        return redirect()
            ->to(admin_route('warehouses.index'))
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()
            ->to(admin_route('warehouses.index'))
            ->with('success', 'Warehouse deleted successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No warehouses selected.');
        }

        Warehouse::whereIn('id', $ids)->delete();

        return redirect()
            ->to(admin_route('warehouses.index'))
            ->with('success', 'Selected warehouses deleted successfully.');
    }
    private function validatedData(Request $request, $warehouseId = null): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:50|unique:warehouses,code,' . $warehouseId,

            'address'       => 'nullable|string',
            'city'          => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
            'country'       => 'nullable|string|max:100',
            'pincode'       => 'nullable|string|max:20',

            'manager_name'  => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',

            'notes'         => 'nullable|string',

            'status'        => 'required|in:active,inactive',
        ]);
    }
}
