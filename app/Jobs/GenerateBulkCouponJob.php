<?php

namespace App\Jobs;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateBulkCouponJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $platformIds;

    public function __construct($data, $platformIds)
    {
        $this->data = $data;
        $this->platformIds = $platformIds;
    }

    public function handle(): void
    {
        DB::transaction(function () {

            for ($i = 1; $i <= $this->data['quantity']; $i++) {

                do {
                    $code = strtoupper($this->data['prefix']) . '-' . strtoupper(Str::random(6));
                } while (Coupon::where('code', $code)->exists());

                $coupon = Coupon::create([
                    'name' => $this->data['coupon_name'],
                    'description' => $this->data['coupon_description'] ?? null,
                    'code' => $code,
                    'coupon_type' => $this->data['coupon_type'],
                    'discount_type' => $this->data['discount_type'],
                    'value' => $this->data['value'],
                    'min_order_amount' => $this->data['min_order_amount'] ?? null,
                    'max_discount' => $this->data['max_discount'] ?? null,
                    'usage_limit' => $this->data['usage_limit'] ?? null,
                    'used_count' => 0,
                    'is_active' => $this->data['is_active'],
                    'starts_at' => $this->data['starts_at'] ?? null,
                    'expires_at' => $this->data['expires_at'] ?? null,
                    'bank_id' => $this->data['coupon_type'] === 'BANK'
                        ? ($this->data['bank_id'] ?? null)
                        : null,
                    'card_type' => $this->data['coupon_type'] === 'BANK'
                        ? ($this->data['card_type'] ?? null)
                        : null,
                    'category_id' => $this->data['category_id'] ?? null,
                    'subcategory_id' => $this->data['subcategory_id'] ?? null,
                    'product_id' => $this->data['product_id'] ?? null,
                    'one_time_per_user' => $this->data['one_time_per_user'],
                ]);

                $coupon->platforms()->sync($this->platformIds);
            }

        });
    }
}