<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            if (Schema::hasColumn('purchase_orders', 'order_date')) {

                $table->dropColumn('order_date');

            }

            if (Schema::hasColumn('purchase_orders', 'expected_delivery_date')) {

                $table->dropColumn('expected_delivery_date');

            }

            if (Schema::hasColumn('purchase_orders', 'payment_terms')) {

                $table->dropColumn('payment_terms');

            }

        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            if (!Schema::hasColumn('purchase_orders', 'order_date')) {

                $table->date('order_date')->nullable();

            }

            if (!Schema::hasColumn('purchase_orders', 'expected_delivery_date')) {

                $table->date('expected_delivery_date')->nullable();

            }

            if (!Schema::hasColumn('purchase_orders', 'payment_terms')) {

                $table->string('payment_terms')->nullable();

            }

        });
    }
};