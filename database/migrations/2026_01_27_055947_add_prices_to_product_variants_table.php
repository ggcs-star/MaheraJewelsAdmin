<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('purchase_price', 10, 2)
                ->after('quantity')
                ->default(0);

            $table->decimal('selling_price', 10, 2)
                ->after('purchase_price')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'selling_price']);
        });
    }
};
