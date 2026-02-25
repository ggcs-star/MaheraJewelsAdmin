<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // Banner Text
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            // Images (ONLY PATH will be stored)
            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();

            // CTA
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();

            // Placement
            $table->string('page');          // home, category, product
            $table->string('position');      // hero, mid, bottom
            $table->string('layout')->default('right'); // right, left, center

            // Styling
            $table->string('text_color')->default('#ffffff');

            // Control
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);

            // Optional scheduling
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};