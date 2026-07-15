<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {

            if (!Schema::hasColumn('coupons', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('card_type')
                    ->constrained('categories')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('coupons', 'subcategory_id')) {
                $table->foreignId('subcategory_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('categories')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('coupons', 'product_id')) {
                $table->foreignId('product_id')
                    ->nullable()
                    ->after('subcategory_id')
                    ->constrained('products')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('coupons', 'one_time_per_user')) {
                $table->boolean('one_time_per_user')
                    ->default(false)
                    ->after('product_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {

            if (Schema::hasColumn('coupons', 'category_id')) {
                $table->dropForeign(['category_id']);
            }

            if (Schema::hasColumn('coupons', 'subcategory_id')) {
                $table->dropForeign(['subcategory_id']);
            }

            if (Schema::hasColumn('coupons', 'product_id')) {
                $table->dropForeign(['product_id']);
            }

            $columns = [];

            if (Schema::hasColumn('coupons', 'category_id')) {
                $columns[] = 'category_id';
            }

            if (Schema::hasColumn('coupons', 'subcategory_id')) {
                $columns[] = 'subcategory_id';
            }

            if (Schema::hasColumn('coupons', 'product_id')) {
                $columns[] = 'product_id';
            }

            if (Schema::hasColumn('coupons', 'one_time_per_user')) {
                $columns[] = 'one_time_per_user';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};