<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('platform_sku')->nullable();
            $table->decimal('platform_price', 10, 2)->nullable();
            $table->integer('platform_stock')->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['platform_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_products');
    }
};
