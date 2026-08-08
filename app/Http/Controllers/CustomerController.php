<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
        ->where('organization_id', activeOrganization()->id)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) =>
                $q->where('is_active', $request->status)
            )
            ->orderBy('name')
            ->when(
                $request->filled('adv_field') &&
                $request->filled('adv_condition') &&
                $request->filled('adv_value'),

                function ($q) use ($request) {

                    $field = $request->adv_field;
                    $condition = $request->adv_condition;
                    $value = trim($request->adv_value);

                    if (!in_array($field, ['name', 'email', 'mobile', 'city'])) {
                        return;
                    }

                    switch ($condition) {

                        case '=':
                            $q->where($field, '=', $value);
                            break;

                        case '!=':
                            $q->where($field, '!=', $value);
                            break;

                        case 'starts_with':
                            $q->where($field, 'like', $value . '%');
                            break;

                        case 'ends_with':
                            $q->where($field, 'like', '%' . $value);
                            break;

                        default: // like
                            $q->where($field, 'like', '%' . $value . '%');
                            break;
                    }
                }
            )
            ->paginate(10)
            ->appends($request->query());

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['organization_id'] = activeOrganization()->id;

        Customer::create($data);


        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer added successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
       $data = $this->validateData($request);
        $data['organization_id'] = $customer->organization_id ?? activeOrganization()->id;

        $customer->update($data);


        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }

    public function bulkDelete(Request $request)
    {
        if (!$request->ids) {
            return back()->with('error', 'No customers selected.');
        }

        Customer::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected customers deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
    'name'   => 'required|string|max:255',
    'email'  => 'nullable|email|max:255',
    'mobile' => [
        'required',
        'digits:10',
        'regex:/^[6-9][0-9]{9}$/'
    ],

    'address_line_1' => 'required|string|max:255',
    'address_line_2' => 'nullable|string|max:255',

    'city'    => 'required|string|max:100',
    'state'   => 'required|string|max:100',
    'country' => 'required|string|max:100',

    'zip_code' => 'required|digits_between:5,6',

    'is_active' => 'required|boolean',
]);
    }
    public function ajaxStore(Request $request)
{
    $data = $request->validate([
        'name'    => 'required|string',
        'mobile'  => 'nullable|string',
        'address_line_1' => 'nullable|string',
    ]);

    $data['organization_id'] = activeOrganization()->id;

return Customer::create($data);

}

}
