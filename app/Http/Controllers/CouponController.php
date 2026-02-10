<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Bank;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::with(['bank', 'platforms'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('code', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->coupon_type, function ($q) use ($request) {
                $q->where('coupon_type', $request->coupon_type);
            })
            ->when($request->status !== null && $request->status !== '', function ($q) use ($request) {
                $q->where('is_active', $request->status);
            })
            ->when(
                $request->adv_field && $request->adv_condition && $request->adv_value,
                function ($q) use ($request) {
                    $allowed = ['name', 'code', 'coupon_type'];
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
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $platforms = Platform::where('is_enabled', true)->orderBy('name')->get();

        return view('coupons.push', compact('banks', 'platforms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coupon_name'        => 'required|string|max:255',
            'coupon_description' => 'nullable|string',
            'code'               => 'required|string|max:50|unique:coupons,code',
            'coupon_type'        => 'required|in:NORMAL,BANK',
            'discount_type'      => 'required|in:FLAT,PERCENT',
            'value'              => 'required|numeric|min:0',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'max_discount'       => 'nullable|numeric|min:0',
            'usage_limit'        => 'nullable|integer|min:1',
            'starts_at'          => 'nullable|date',
            'expires_at'         => 'nullable|date|after_or_equal:starts_at',
            'is_active'          => 'required|boolean',
            'bank_id'            => 'nullable|exists:banks,id',
            'card_type'          => 'nullable|in:credit,debit,both',
        ]);

        DB::transaction(function () use ($data, $request) {
            $coupon = Coupon::create([
                'name'             => $data['coupon_name'],
                'description'      => $data['coupon_description'] ?? null,
                'code'             => strtoupper($data['code']),
                'coupon_type'      => $data['coupon_type'],
                'discount_type'    => $data['discount_type'],
                'value'            => $data['value'],
                'min_order_amount' => $data['min_order_amount'] ?? null,
                'max_discount'     => $data['max_discount'] ?? null,
                'usage_limit'      => $data['usage_limit'] ?? null,
                'used_count'       => 0,
                'is_active'        => $data['is_active'],
                'starts_at'        => $data['starts_at'] ?? null,
                'expires_at'       => $data['expires_at'] ?? null,
                'bank_id'          => $data['coupon_type'] === 'BANK' ? $data['bank_id'] : null,
                'card_type'        => $data['coupon_type'] === 'BANK' ? $data['card_type'] : null,
            ]);

            if ($request->filled('platform_ids')) {
                $coupon->platforms()->sync($request->platform_ids);
            }
        });

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['bank', 'platforms']);
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $banks = Bank::where('status', 1)->get();
        $platforms = Platform::where('is_enabled', true)->get();

        $coupon->load(['bank', 'platforms']);

        return view('coupons.edit', compact('coupon', 'banks', 'platforms'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        DB::transaction(function () use ($request, $coupon) {
            $coupon->update([
                'name'             => $request->coupon_name,
                'description'      => $request->coupon_description,
                'discount_type'    => $request->discount_type,
                'value'            => $request->value,
                'min_order_amount' => $request->min_order_amount,
                'max_discount'     => $request->max_discount,
                'usage_limit'      => $request->usage_limit,
                'is_active'        => $request->is_active,
                'starts_at'        => $request->starts_at,
                'expires_at'       => $request->expires_at,
                'bank_id'          => $coupon->coupon_type === 'BANK' ? $request->bank_id : null,
                'card_type'        => $coupon->coupon_type === 'BANK' ? $request->card_type : null,
            ]);

            $coupon->platforms()->sync($request->platform_ids ?? []);
        });

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->platforms()->detach();
        $coupon->delete();

        return back()->with('success', 'Coupon deleted');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (count($ids)) {
            Coupon::whereIn('id', $ids)->delete();
        }

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Selected coupons deleted successfully');
    }
}
