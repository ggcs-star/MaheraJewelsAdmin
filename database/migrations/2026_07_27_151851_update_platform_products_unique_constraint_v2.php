<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('platform_products', function (Blueprint $table) {
            // ✅ PEHLE FOREIGN KEY HATAO
            $table->dropForeign(['platform_id']);
            
            // ✅ PHIR INDEX DROP KARO
            $table->dropUnique(['platform_id', 'product_id']);
            
            // ✅ NAYA UNIQUE CONSTRAINT ADD KARO
            $table->unique(['platform_id', 'product_id', 'product_variant_id'], 'platform_products_platform_product_variant_unique');
            
            // ✅ FOREIGN KEY WAPAS ADD KARO
            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('platform_products', function (Blueprint $table) {
            $table->dropForeign(['platform_id']);
            $table->dropUnique('platform_products_platform_product_variant_unique');
            $table->unique(['platform_id', 'product_id']);
            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('cascade');
        });
    }
};