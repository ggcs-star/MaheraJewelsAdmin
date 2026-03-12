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
        if (Schema::hasTable('reel_likes')) {

            Schema::table('reel_likes', function (Blueprint $table) {

                // Add ip_address if not exists
                if (!Schema::hasColumn('reel_likes', 'ip_address')) {
                    $table->string('ip_address')->nullable()->after('user_id');
                }

                // Make user_id nullable if needed
                if (Schema::hasColumn('reel_likes', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                }

            });

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('reel_likes')) {

            Schema::table('reel_likes', function (Blueprint $table) {

                if (Schema::hasColumn('reel_likes', 'ip_address')) {
                    $table->dropColumn('ip_address');
                }

            });

        }
    }
};