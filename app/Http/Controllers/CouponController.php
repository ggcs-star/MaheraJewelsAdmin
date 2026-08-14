<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Bank;
use App\Models\Platform;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::with([
            'bank',
            'platforms',
            'category',
            'subCategory',
            'product'
        ])
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
        // ✅ YEH NAYA CODE ADD KARO - Category Filter
        ->when($request->category_id, function ($q) use ($request) {
            $q->where(function ($qq) use ($request) {
                $qq->where('category_id', $request->category_id)
                ->orWhere('subcategory_id', $request->category_id);
            });
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

        
        $categories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            

        return view('coupons.index', compact('coupons', 'categories'));
    }
    public function create()
    {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();

        $platforms = Platform::where('is_enabled', true)
            ->orderBy('name')
            ->get();

    $categories = Category::whereNull('parent_id')
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        $subCategories = collect();

        $products = collect();

        return view('coupons.push', compact(
            'banks',
            'platforms',
            'categories',
            'subCategories',
            'products'
        ));
    }

    public function store(Request $request)
    {

    $validator = Validator::make($request->all(), [

        'coupon_name'        => 'required|string|max:255',
        'coupon_description' => 'nullable|string',
        'generate_type' => 'required|in:single,bulk',

    'code' => $request->generate_type == 'single'
        ? 'required|string|max:50|unique:coupons,code'
        : 'nullable',

    'campaign_name' => 'nullable|required_if:generate_type,bulk|string|max:255',

    'prefix' => 'nullable|required_if:generate_type,bulk|string|max:20',

    'quantity' => 'nullable|required_if:generate_type,bulk|integer|min:1|max:10000',
        'coupon_type'        => 'required|in:NORMAL,BANK',
        'discount_type'      => 'required|in:FLAT,PERCENT',
        'value'              => 'required|numeric|min:0',
        'min_order_amount'   => 'nullable|numeric|min:0',
        'usage_limit'        => 'required|integer|min:1',
        'starts_at'          => 'required|date',
        'expires_at'         => 'required|date|after_or_equal:starts_at',
        'max_discount'       => 'nullable|numeric|min:0',
        'is_active'          => 'required|boolean',
        'bank_id' => 'nullable|required_if:coupon_type,BANK|exists:banks,id',
        'card_type' => 'nullable|required_if:coupon_type,BANK|in:credit,debit,both',
        'platform_ids'       => 'required|array|min:1',
        'platform_ids.*'     => 'exists:platforms,id',
        'category_id'        => 'nullable|exists:categories,id',
        'subcategory_id'     => 'nullable|exists:categories,id',
        'product_id'         => 'nullable|exists:products,id',
        'one_time_per_user'  => 'required|boolean',

    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    $data = $validator->validated();
    // if ($request->generate_type == 'bulk') {

    //     GenerateBulkCouponJob::dispatch(
    //         $data,
    //         $request->platform_ids
    //     );

    //     return redirect()
    //         ->route('admin.coupons.index')
    //         ->with('success', 'Bulk Coupon Generation Started.');
    // }
    if ($request->generate_type == 'bulk') {

        DB::transaction(function () use ($data, $request) {

            for ($i = 1; $i <= $data['quantity']; $i++) {

            do {
                $code = strtoupper($data['prefix']) . strtoupper(Str::random(8));
            } while (Coupon::where('code', $code)->exists());

            $coupon = Coupon::create([
                'name'               => $data['coupon_name'],
                'description'        => $data['coupon_description'] ?? null,
                'code'               => $code,
                'campaign_name' => $data['campaign_name'],
                'coupon_type'        => $data['coupon_type'],
                'discount_type'      => $data['discount_type'],
                'value'              => $data['value'],
                'min_order_amount'   => $data['min_order_amount'] ?? null,
                'max_discount'       => $data['max_discount'] ?? null,
                'usage_limit'        => $data['usage_limit'],
                'used_count'         => 0,
                'is_active'          => $data['is_active'],
                'starts_at'          => $data['starts_at'] ?? null,
                'expires_at'         => $data['expires_at'] ?? null,
                'bank_id'            => $data['coupon_type'] === 'BANK' ? $data['bank_id'] : null,
                'card_type'          => $data['coupon_type'] === 'BANK' ? $data['card_type'] : null,
                'category_id'        => $data['category_id'] ?? null,
                'subcategory_id'     => $data['subcategory_id'] ?? null,
                'product_id'         => $data['product_id'] ?? null,
                'one_time_per_user'  => $data['one_time_per_user'],
            ]);

            if ($request->filled('platform_ids')) {
                $coupon->platforms()->sync($request->platform_ids);
            }
        }

    });

    return redirect()
        ->route('admin.coupons.index')
        ->with('success', 'Bulk Coupons Generated Successfully.');
    }
        DB::transaction(function () use ($data, $request) {
            $coupon = Coupon::create([
                'name'             => $data['coupon_name'],
                'description'      => $data['coupon_description'] ?? null,
                'code'             => strtoupper($data['code']),
                'coupon_type'      => $data['coupon_type'],
                'discount_type'    => $data['discount_type'],
                'value'            => $data['value'],
                'min_order_amount'   => $data['min_order_amount'] ?? null,
                'max_discount'     => $data['max_discount'] ?? null,
                'usage_limit'      => $data['usage_limit'] ?? null,
                'used_count'       => 0,
                'is_active'        => $data['is_active'],
                'starts_at'        => $data['starts_at'] ?? null,
                'expires_at'       => $data['expires_at'] ?? null,
                'bank_id'          => $data['coupon_type'] === 'BANK' ? $data['bank_id'] : null,
                'card_type'        => $data['coupon_type'] === 'BANK' ? $data['card_type'] : null,
                'category_id'       => $data['category_id'] ?? null,
                'subcategory_id'    => $data['subcategory_id'] ?? null,
                'product_id'        => $data['product_id'] ?? null,
                'one_time_per_user' => $data['one_time_per_user'],
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
        $coupon->load([
            'bank',
            'platforms',
            'category',
            'subCategory',
            'product'
        ]);
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $banks = Bank::where('status', 1)->orderBy('name')->get();

        $platforms = Platform::where('is_enabled', true)
            ->orderBy('name')
            ->get();
        $categories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

    $subCategories = Category::where('parent_id', $coupon->category_id)
        ->orderBy('name')
        ->get();
    $products = Product::where('category_id', $coupon->subcategory_id)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        $coupon->load([
            'bank',
            'platforms',
            'category',
            'subCategory',
            'product'
        ]);

        return view('coupons.edit', compact(
            'coupon',
            'banks',
            'platforms',
            'categories',
            'subCategories',
            'products'
        ));
    }
    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'coupon_name'        => 'required|string|max:255',
            'coupon_description' => 'nullable|string',
            'discount_type'      => 'required|in:FLAT,PERCENT',
            'coupon_type' => 'required|in:NORMAL,BANK',
            'value'              => 'required|numeric|min:0',
             'min_order_amount'   => 'nullable|numeric|min:0', 
            'usage_limit'        => 'required|integer|min:1',
            'starts_at'          => 'required|date',
            'expires_at'         => 'required|date|after_or_equal:starts_at',
            'max_discount'       => 'nullable|numeric|min:0',
            'is_active'          => 'required|boolean',
            'bank_id' => 'nullable|required_if:coupon_type,BANK|exists:banks,id',
            'card_type' => 'nullable|required_if:coupon_type,BANK|in:credit,debit,both',
            'platform_ids'       => 'required|array|min:1',
            'platform_ids.*'     => 'exists:platforms,id',
            'category_id'        => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'product_id'         => 'nullable|exists:products,id',
            'one_time_per_user'  => 'required|boolean',
        ]);

        DB::transaction(function () use ($coupon, $data) {

            $coupon->update([
                'name'               => $data['coupon_name'],
                'description'        => $data['coupon_description'] ?? null,
                'discount_type'      => $data['discount_type'],
                'value'              => $data['value'],
                'min_order_amount'   => $data['min_order_amount'] ?? null, 
                'max_discount'       => $data['max_discount'] ?? null,
                'usage_limit'        => $data['usage_limit'],
                'is_active'          => $data['is_active'],
                'starts_at'          => $data['starts_at'],
                'expires_at'         => $data['expires_at'],
        'coupon_type' => $data['coupon_type'],

        'bank_id' => $data['coupon_type'] === 'BANK'
            ? ($data['bank_id'] ?? null)
            : null,

        'card_type' => $data['coupon_type'] === 'BANK'
            ? ($data['card_type'] ?? null)
            : null,
                'category_id'        => $data['category_id'] ?? null,
                'subcategory_id'     => $data['subcategory_id'] ?? null,
                'product_id'         => $data['product_id'] ?? null,
                'one_time_per_user'  => $data['one_time_per_user'],
            ]);

            $coupon->platforms()->sync($data['platform_ids']);
        });

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }
    public function getSubCategories($categoryId)
    {
        return Category::where('parent_id', $categoryId)
            ->orderBy('name')
            ->get();
    }
    public function getProducts($subcategoryId)
    {
        return Product::where('category_id', $subcategoryId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
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
            $coupons = Coupon::whereIn('id', $ids)->get();

        foreach ($coupons as $coupon) {
            $coupon->platforms()->detach();
            $coupon->delete();
        }

            }

            return redirect()
                ->route('admin.coupons.index')
                ->with('success', 'Selected coupons deleted successfully');
        }
    }
