<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('reels', function (Blueprint $table) {

            // new views column
            $table->unsignedBigInteger('views_count')->default(0);

            // other counters
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);

        });

        Schema::table('reels', function (Blueprint $table) {

            // remove old column
            $table->dropColumn('views');

        });

    }

    public function down(): void
    {

        Schema::table('reels', function (Blueprint $table) {

            $table->unsignedBigInteger('views')->default(0);

            $table->dropColumn([
                'views_count',
                'likes_count',
                'shares_count'
            ]);

        });

    }
};