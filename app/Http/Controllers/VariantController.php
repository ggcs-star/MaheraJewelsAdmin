<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /* =======================
       VARIANT MASTER
    ======================== */

    public function index()
    {
        return view('variants.index', [
            'variants' => Variant::with('values')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'slug'       => 'required|unique:variants,slug',
            'input_type' => 'required',
        ]);

        Variant::create($request->only('name', 'slug', 'input_type'));

        return back();
    }

    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'name'       => 'required',
            'slug'       => 'required|unique:variants,slug,' . $variant->id,
            'input_type' => 'required',
        ]);

        $variant->update(
            $request->only('name', 'slug', 'input_type')
        );

        return back();
    }

    public function destroy(Variant $variant)
    {
        $variant->delete(); // values auto delete (cascade)
        return back();
    }

    /* =======================
       VARIANT VALUES
    ======================== */

    public function storeValue(Request $request, Variant $variant)
    {
        $request->validate([
            'value' => 'required',
            'extra' => 'nullable',
        ]);

        $variant->values()->create(
            $request->only('value', 'extra')
        );

        return back();
    }

    public function updateValue(Request $request, VariantValue $value)
    {
        $request->validate([
            'value' => 'required',
            'extra' => 'nullable',
        ]);

        $value->update(
            $request->only('value', 'extra')
        );

        return back();
    }

    public function destroyValue(VariantValue $value)
    {
        $value->delete();
        return back();
    }
    public function getValues(\App\Models\Variant $variant)
{
    return response()->json(
        $variant->values()->select('id', 'value')->get()
    );
}

}
