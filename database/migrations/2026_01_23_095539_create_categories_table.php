<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('image_url', 500)->nullable();

            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->string('visibility', 20)->default('public');
            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
