<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // enum hata ke string bana do
            $table->string('variant_type')->change();

            // new field add
            $table->integer('quantity')->default(0)->after('variant_value');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            // wapas enum (agar rollback kare)
            $table->enum('variant_type', ['size', 'color'])->change();

            // remove quantity
            $table->dropColumn('quantity');
        });
    }
};
