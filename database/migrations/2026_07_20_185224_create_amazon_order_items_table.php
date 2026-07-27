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
        Schema::create('amazon_order_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignId('amazon_order_db_id')
                ->constrained('amazon_orders')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Amazon Information
            |--------------------------------------------------------------------------
            */

            $table->string('amazon_order_id')->index();

            $table->string('amazon_order_item_id')->unique();

            /*
            |--------------------------------------------------------------------------
            | Product Information
            |--------------------------------------------------------------------------
            */

            $table->string('seller_sku')->nullable()->index();

            $table->string('asin')->nullable()->index();

            $table->string('title')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity_ordered')->default(0);

            $table->unsignedInteger('quantity_shipped')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            $table->decimal('item_price', 12, 2)->default(0);

            $table->string('currency', 10)->default('INR');

            /*
            |--------------------------------------------------------------------------
            | ERP Mapping
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('product_id')->nullable();

            $table->unsignedBigInteger('product_variant_id')->nullable();

            $table->boolean('sku_matched')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */

            $table->boolean('inventory_updated')->default(false);

            $table->timestamp('inventory_updated_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amazon Raw Response
            |--------------------------------------------------------------------------
            */

            $table->json('raw_response')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Additional Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('product_variant_id');
            $table->index('inventory_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_order_items');
    }
};