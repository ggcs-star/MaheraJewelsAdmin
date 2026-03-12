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

        Schema::create('reels', function (Blueprint $table) {

            $table->id();

            // Product Relation
            $table->foreignId('platform_product_id')
                ->constrained('platform_products')
                ->cascadeOnDelete();

            // Reel Title
            $table->string('title')->nullable();

            // Reel Description
            $table->text('description')->nullable();

            // Video URL (S3)
            $table->string('video');

            // Status
            $table->boolean('status')->default(1);

            // Views (future analytics)
            $table->unsignedBigInteger('views')->default(0);

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('reels');

    }
};