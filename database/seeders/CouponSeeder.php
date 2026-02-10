<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\Platform;
use App\Models\Bank;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $platformIds = Platform::pluck('id')->toArray();
        $banks       = Bank::all()->keyBy('code');

        if (empty($platformIds) || $banks->isEmpty()) {
            return;
        }
        $normalCoupons = [
            ['name' => 'Save ₹100 on Orders', 'code' => 'SAVE100', 'type' => 'FLAT', 'value' => 100],
            ['name' => 'Flat 20% OFF',        'code' => 'FLAT20',  'type' => 'PERCENT', 'value' => 20],
            ['name' => 'Mega Sale Discount',  'code' => 'MEGA500', 'type' => 'FLAT', 'value' => 500],
            ['name' => 'Welcome Offer',       'code' => 'WELCOME10','type' => 'PERCENT','value' => 10],
            ['name' => 'Festival Special',    'code' => 'DIWALI25','type' => 'PERCENT','value' => 25],
            ['name' => 'Cart Value Offer',    'code' => 'CART200', 'type' => 'FLAT','value' => 200],
            ['name' => 'Weekend Deal',        'code' => 'WEEKEND15','type'=>'PERCENT','value'=>15],
            ['name' => 'Limited Time Offer',  'code' => 'LIMITED300','type'=>'FLAT','value'=>300],
            ['name' => 'Hot Deal',             'code' => 'HOT10','type'=>'PERCENT','value'=>10],
            ['name' => 'Super Saver',          'code' => 'SUPER150','type'=>'FLAT','value'=>150],
        ];

        foreach ($normalCoupons as $data) {

            $coupon = Coupon::create([
                'name'             => $data['name'],
                'description'      => $data['name'],
                'code'             => $data['code'],
                'coupon_type'      => 'NORMAL',
                'discount_type'    => $data['type'],
                'value'            => $data['value'],
                'min_order_amount' => 999,
                'max_discount'     => 500,
                'usage_limit'      => 200,
                'used_count'       => 0,
                'is_active'        => 1,
                'starts_at'        => now()->subDays(2),
                'expires_at'       => now()->addDays(45),
            ]);

            $coupon->platforms()->attach($platformIds);
        }
        $bankCoupons = [
            ['bank' => 'HDFC',  'code' => 'HDFC10',  'value' => 10],
            ['bank' => 'ICICI', 'code' => 'ICICI15','value' => 15],
            ['bank' => 'SBI',   'code' => 'SBI20',  'value' => 20],
            ['bank' => 'KOTAK', 'code' => 'KOTAK10','value' => 10],
            ['bank' => 'AXIS',  'code' => 'AXIS500','value' => 500],
        ];

        foreach ($bankCoupons as $data) {

            if (!isset($banks[$data['bank']])) continue;

          $coupon = Coupon::create([
    'name'             => $data['bank'] . ' Bank Offer',
    'description'      => 'Extra discount on ' . $data['bank'] . ' cards',
    'code'             => $data['code'],
    'coupon_type'      => 'BANK',
    'discount_type'    => is_numeric($data['value']) && $data['value'] <= 100 ? 'PERCENT' : 'FLAT',
    'value'            => $data['value'],
    'min_order_amount' => 1000,
    'max_discount'     => 500,
    'usage_limit'      => 300,
    'used_count'       => 0,
    'is_active'        => 1,
    'starts_at'        => now(),
    'expires_at'       => now()->addDays(60),
    'bank_id'          => $banks[$data['bank']]->id,
    'card_type'        => 'credit',
]);

// 🔥 THIS WAS MISSING
$coupon->platforms()->attach($platformIds);

        }
    }
}
