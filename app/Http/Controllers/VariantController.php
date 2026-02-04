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
        'name'           => 'required',
        'input_type'     => 'required',
        'has_dimensions' => 'required|boolean',
    ]);

    Variant::create([
        'name'           => $request->name,
        'input_type'     => $request->input_type,
        'has_dimensions' => $request->has_dimensions,
        'is_active'      => 1,
    ]);

    return back();
}

public function update(Request $request, Variant $variant)
{
    $request->validate([
        'name'           => 'required',
        'input_type'     => 'required',
        'has_dimensions' => 'required|boolean',
    ]);

    $variant->update([
        'name'           => $request->name,
        'input_type'     => $request->input_type,
        'has_dimensions' => $request->has_dimensions,
    ]);

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
    $rules = [
        'value' => 'required',
    ];

    // 🔥 agar variant has_dimensions = YES
    if ($variant->has_dimensions) {
        $rules['height'] = 'required|numeric';
        $rules['width']  = 'required|numeric';
    }

    $request->validate($rules);

    $variant->values()->create([
        'value'  => $request->value,
        'color'  => $request->color ?? null,
        'height' => $variant->has_dimensions ? $request->height : null,
        'width'  => $variant->has_dimensions ? $request->width : null,
        'is_active' => 1,
    ]);

    return back();
}

public function updateValue(Request $request, VariantValue $value)
{
    $variant = $value->variant;

    $rules = [
        'value' => 'required',
    ];

    if ($variant->has_dimensions) {
        $rules['height'] = 'required|numeric';
        $rules['width']  = 'required|numeric';
    }

    $request->validate($rules);

    $value->update([
        'value'  => $request->value,
        'color'  => $request->color ?? null,
        'height' => $variant->has_dimensions ? $request->height : null,
        'width'  => $variant->has_dimensions ? $request->width : null,
    ]);

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
