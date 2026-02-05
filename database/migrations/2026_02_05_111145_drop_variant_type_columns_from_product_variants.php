<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Remove duplicated snapshot fields
            if (Schema::hasColumn('product_variants', 'variant_type')) {
                $table->dropColumn('variant_type');
            }

            if (Schema::hasColumn('product_variants', 'variant_value')) {
                $table->dropColumn('variant_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Recreate if rollback happens
            $table->string('variant_type')->nullable();
            $table->string('variant_value')->nullable();
        });
    }
};
