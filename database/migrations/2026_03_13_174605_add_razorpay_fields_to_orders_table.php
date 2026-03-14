<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders','razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders','payment_method')) {
                $table->string('payment_method')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'razorpay_order_id',
                'payment_method'
            ]);

        });
    }
};