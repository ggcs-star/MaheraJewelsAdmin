<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::connection('social_mysql')->table('likes', function (Blueprint $table) {

            // unique constraint pehle remove karna padega
            $table->dropUnique(['user_id', 'link_id']);

            // column drop
            if (Schema::connection('social_mysql')->hasColumn('likes', 'user_id')) {
                $table->dropColumn('user_id');
            }

            // optional: new unique only on link_id (not recommended)
            // $table->unique('link_id');
        });
    }

    public function down(): void
    {
        Schema::connection('social_mysql')->table('likes', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id');

            $table->unique(['user_id', 'link_id']);
        });
    }
};