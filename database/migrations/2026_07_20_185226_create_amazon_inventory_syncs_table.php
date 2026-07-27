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
        Schema::create('amazon_inventory_syncs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Product Mapping
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('product_id')->nullable()->index();

            $table->unsignedBigInteger('product_variant_id')->nullable()->index();

            $table->string('seller_sku')->index();

            $table->string('asin')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */

            $table->integer('amazon_quantity')->default(0);

            $table->integer('erp_quantity_before')->default(0);

            $table->integer('erp_quantity_after')->default(0);

            $table->integer('quantity_difference')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Sync Information
            |--------------------------------------------------------------------------
            */

            $table->enum('sync_type', [
                'inventory_pull',
                'inventory_push',
                'manual'
            ])->default('inventory_pull');

            $table->enum('sync_status', [
                'success',
                'failed',
                'skipped'
            ])->default('success');

            $table->text('message')->nullable();

            $table->timestamp('synced_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Raw API Response
            |--------------------------------------------------------------------------
            */

            $table->json('raw_response')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('sync_type');
            $table->index('sync_status');
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_inventory_syncs');
    }
};