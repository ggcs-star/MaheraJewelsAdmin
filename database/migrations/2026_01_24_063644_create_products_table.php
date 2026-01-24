<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();

            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

            $table->string('brand')->nullable();

            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('base_selling_price', 10, 2)->default(0);

            $table->string('image_url')->nullable();
            $table->json('gallery_images')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_top_selling')->default(false);

            $table->enum('visibility', ['public', 'private', 'hidden'])->default('public');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
