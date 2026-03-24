<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('social_mysql')->create('comments', function (Blueprint $table) {
            $table->id();

            $table->string('link_id');
            $table->string('username'); // 🔥 important
            $table->text('comment');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('social_mysql')->dropIfExists('comments');
    }
};