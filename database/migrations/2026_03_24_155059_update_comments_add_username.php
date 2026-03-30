<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('social_mysql')->table('comments', function (Blueprint $table) {
            // 🔥 new column
            $table->string('username')->nullable()->after('link_id');
        });
    }

    public function down(): void
    {
        Schema::connection('social_mysql')->table('comments', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};