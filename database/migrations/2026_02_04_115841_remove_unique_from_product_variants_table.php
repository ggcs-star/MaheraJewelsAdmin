<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // 1️⃣ Drop foreign key FIRST
            $table->dropForeign(['product_id']);

            // 2️⃣ Drop unique index
            $table->dropUnique('product_variants_product_id_variant_type_variant_value_unique');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // 1️⃣ Recreate unique index
            $table->unique(
                ['product_id', 'variant_type', 'variant_value'],
                'product_variants_product_id_variant_type_variant_value_unique'
            );

            // 2️⃣ Recreate foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
};
