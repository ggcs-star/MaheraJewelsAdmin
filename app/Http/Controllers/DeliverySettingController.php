<?php

namespace App\Http\Controllers;

use App\Models\DeliverySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliverySettingController extends Controller
{

    public function index(Request $request)
    {

        $settings = DeliverySetting::query()

        ->when($request->filled('search'), function ($q) use ($request){

        $search = trim($request->search);

        $q->where(function($qq) use ($search){

        $qq->where('delivery_fee','LIKE',"%{$search}%")
        ->orWhere('platform_fee','LIKE',"%{$search}%")
        ->orWhere('tax_percent','LIKE',"%{$search}%");

        });

        })

        ->when(

        $request->filled('adv_field') &&
        $request->filled('adv_condition') &&
        $request->filled('adv_value'),

        function ($q) use ($request){

        $field = $request->adv_field;
        $cond  = $request->adv_condition;
        $value = trim($request->adv_value);

        switch($cond){

        case 'like':
            $q->where($field,'LIKE',"%{$value}%");
        break;

        case 'starts_with':
            $q->where($field,'LIKE',"{$value}%");
        break;

        case 'ends_with':
            $q->where($field,'LIKE',"%{$value}");
        break;

        case '=':
            $q->where($field,'=',$value);
        break;

        case '!=':
            $q->where($field,'!=',$value);
        break;

        }

    }

        )

        ->orderByDesc('id')
        ->paginate(10)
        ->appends($request->query());

        return view('delivery-settings.index',compact('settings'));

        }

    public function create()
    {
        return view('delivery-settings.create');
    }

    public function show(DeliverySetting $deliverySetting)
    {
        return view('delivery-settings.show', compact('deliverySetting'));
    }

    public function store(Request $request)
    {
        try {

            $data = $this->validated($request);

            DeliverySetting::create($data);

            return redirect()
                ->to(admin_route('delivery-settings.index'))
                ->with('success', 'Delivery setting created successfully.');

        } catch (\Exception $e) {

            Log::error('DeliverySetting store failed', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Something went wrong while saving.');
        }
    }

    public function edit(DeliverySetting $deliverySetting)
    {
        return view('delivery-settings.edit', compact('deliverySetting'));
    }

    public function update(Request $request, DeliverySetting $deliverySetting)
    {
        try {

            $data = $this->validated($request);

            $deliverySetting->update($data);

            return redirect()
                ->to(admin_route('delivery-settings.index'))
                ->with('success', 'Delivery setting updated successfully.');

        } catch (\Exception $e) {

            Log::error('DeliverySetting update failed', [
                'id' => $deliverySetting->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Update failed.');
        }
    }

    public function destroy(DeliverySetting $deliverySetting)
    {
        try {

            if(!$deliverySetting){
                return back()->with('error','Record not found');
            }

            $deliverySetting->delete();

            return redirect()
                ->to(admin_route('delivery-settings.index'))
                ->with('success','Delivery setting deleted successfully');

        } catch (\Throwable $e) {

            \Log::error('DeliverySetting delete error',[
                'message'=>$e->getMessage(),
                'id'=>$deliverySetting->id ?? null
            ]);

            return back()->with('error','Delete failed');
        }
    }

    public function bulkDelete(Request $request)
    {
        try {

            $ids = $request->ids ?? [];

            if(empty($ids)){
                return back()->with('error','No settings selected');
            }

            DeliverySetting::whereIn('id',$ids)->delete();

            return redirect()
                ->to(admin_route('delivery-settings.index'))
                ->with('success','Selected settings deleted');

        } catch (\Throwable $e) {

            \Log::error('DeliverySetting bulk delete error',[
                'message'=>$e->getMessage(),
                'ids'=>$request->ids
            ]);

            return back()->with('error','Bulk delete failed');
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([

            'delivery_fee' => 'required|numeric|min:0|max:999999',

            'platform_fee' => 'required|numeric|min:0|max:999999',

            'tax_percent' => 'required|numeric|min:0|max:100',

            'free_delivery_above' => 'nullable|numeric|min:0|max:999999',

        ]);
    }
}