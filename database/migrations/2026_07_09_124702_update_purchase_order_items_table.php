<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {

            if (Schema::hasColumn('purchase_order_items', 'online_price')) {

                $table->dropColumn('online_price');

            }

            if (Schema::hasColumn('purchase_order_items', 'offline_price')) {

                $table->dropColumn('offline_price');

            }

        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {

            $table->decimal('online_price',12,2)->nullable();

            $table->decimal('offline_price',12,2)->nullable();

        });
    }
};