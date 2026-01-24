<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_pricing', function (Blueprint $table) {
            $table->id();

            $table->foreignId('platform_product_id')->constrained('platform_products')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();

            $table->decimal('price', 10, 2);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2);

            $table->string('currency', 10)->default('INR');

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['platform_product_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_pricing');
    }
};
