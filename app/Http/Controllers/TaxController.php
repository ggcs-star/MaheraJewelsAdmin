<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::orderBy('id', 'desc')->get();
        return view('taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'rate'   => 'required|numeric|min:0',
            'type'   => 'required|in:percentage,fixed',
            'status' => 'required|in:active,inactive',
        ]);

        Tax::create($data);

        return redirect()
            ->route('admin.taxes.index')
            ->with('success', 'Tax added successfully');
    }

    public function edit(Tax $tax)
    {
        return view('taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'rate'   => 'required|numeric|min:0',
            'type'   => 'required|in:percentage,fixed',
            'status' => 'required|in:active,inactive',
        ]);

        $tax->update($data);

        return redirect()
            ->route('admin.taxes.index')
            ->with('success', 'Tax updated successfully');
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();

        return redirect()
            ->route('admin.taxes.index')
            ->with('success', 'Tax deleted successfully');
    }
}
