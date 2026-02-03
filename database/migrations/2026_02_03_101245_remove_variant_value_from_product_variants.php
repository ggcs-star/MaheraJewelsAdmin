<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // 1️⃣ foreign key constraints temporarily disable
            Schema::disableForeignKeyConstraints();

            // 2️⃣ unique index drop
            $table->dropUnique(
                'product_variants_product_id_variant_type_variant_value_unique'
            );

            // 3️⃣ column drop
            $table->dropColumn('variant_value');

            // 4️⃣ foreign key constraints enable back
            Schema::enableForeignKeyConstraints();
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('variant_value');

            $table->unique(
                ['product_id', 'variant_type', 'variant_value'],
                'product_variants_product_id_variant_type_variant_value_unique'
            );
        });
    }
};
