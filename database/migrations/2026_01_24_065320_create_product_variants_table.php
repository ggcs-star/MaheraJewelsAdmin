<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->enum('variant_type', ['size', 'color']);
            $table->string('variant_value'); // S, M, L, Red, Blue

            $table->string('sku_suffix')->nullable(); // -S, -RED
            $table->string('image_url')->nullable();

            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Prevent duplicate variant per product
            $table->unique(['product_id', 'variant_type', 'variant_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
