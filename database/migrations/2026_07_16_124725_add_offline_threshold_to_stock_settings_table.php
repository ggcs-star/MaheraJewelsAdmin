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
        Schema::table('stock_settings', function (Blueprint $table) {
            $table->integer('offline_threshold')->default(3)->after('threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_settings', function (Blueprint $table) {
            $table->dropColumn('offline_threshold');
        });
    }
};