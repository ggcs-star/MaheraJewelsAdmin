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
       Schema::create('instagram_reels', function (Blueprint $table) {
    $table->id();

    $table->string('instagram_media_id')->unique();

    $table->longText('caption')->nullable();

    $table->string('media_type')->nullable();

    $table->string('media_product_type')->nullable();

    $table->longText('media_url');

    $table->longText('thumbnail_url')->nullable();

    $table->longText('permalink')->nullable();

    $table->timestamp('instagram_created_at')->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_reels');
    }
};
