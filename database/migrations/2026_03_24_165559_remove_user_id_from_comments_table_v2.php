<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::connection('social_mysql')->table('comments', function (Blueprint $table) {

            // safe drop (error nahi aayega)
            if (Schema::connection('social_mysql')->hasColumn('comments', 'user_id')) {
                $table->dropColumn('user_id');
            }

        });
    }

    public function down(): void
    {
        Schema::connection('social_mysql')->table('comments', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->nullable();

        });
    }
};