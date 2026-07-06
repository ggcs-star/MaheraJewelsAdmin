<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('product_clicks', function (Blueprint $table) {
        $table->unsignedBigInteger('click_count')->default(0)->after('product_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_clicks', function (Blueprint $table) {
            //
        });
    }
};
