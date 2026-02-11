<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
public function index(Request $request)
{
    $banks = Bank::query()
        ->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($qq) use ($request) {
                $qq->where('name', 'like', '%' . $request->search . '%')
                   ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        })
        ->when(
            $request->adv_field && $request->adv_condition && $request->adv_value,
            function ($q) use ($request) {

                $allowed = ['name', 'code', 'status'];

                if (!in_array($request->adv_field, $allowed)) {
                    return;
                }

                if ($request->adv_condition === 'like') {
                    $q->where(
                        $request->adv_field,
                        'like',
                        '%' . $request->adv_value . '%'
                    );
                } else {
                    $q->where(
                        $request->adv_field,
                        $request->adv_condition,
                        $request->adv_value
                    );
                }
            }
        )

        ->orderBy('name')
        ->paginate(10)
        ->appends($request->query());

    return view('banks.index', compact('banks'));
}
    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Bank::create($data);

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank created successfully.');
    }

    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $this->validated($request, $bank->id);

        $bank->update($data);

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()
            ->to(admin_route('banks.index'))
            ->with('success', 'Bank deleted successfully.');
    }

    private function validated(Request $request, $id = null): array
    {
        return $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:banks,code,' . $id,
            'status' => 'required|boolean',
        ]);
    }

public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);

    if (!empty($ids)) {
        Bank::whereIn('id', $ids)->delete();
    }

    return redirect()
        ->admin_route('banks.index')
        ->with('success', 'Selected banks deleted successfully');
}

}