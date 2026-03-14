<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            if (!Schema::hasColumn('payments', 'client_ip')) {
                $table->string('client_ip')->nullable()->after('razorpay_signature');
            }

            if (!Schema::hasColumn('payments', 'coupon_code')) {
                $table->string('coupon_code')->nullable();
            }

            if (!Schema::hasColumn('payments', 'payment_meta')) {
                $table->json('payment_meta')->nullable();
            }

            if (!Schema::hasColumn('payments', 'razorpay_payload')) {
                $table->json('razorpay_payload')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            if (Schema::hasColumn('payments', 'client_ip')) {
                $table->dropColumn('client_ip');
            }

            if (Schema::hasColumn('payments', 'coupon_code')) {
                $table->dropColumn('coupon_code');
            }

            if (Schema::hasColumn('payments', 'payment_meta')) {
                $table->dropColumn('payment_meta');
            }

            if (Schema::hasColumn('payments', 'razorpay_payload')) {
                $table->dropColumn('razorpay_payload');
            }

        });
    }
};