<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('platform_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('platform_products', function (Blueprint $table) {
            $table->dropColumn('product_variant_id');
        });
    }
};