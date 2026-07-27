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
        Schema::create('amazon_orders', function (Blueprint $table) {

            $table->id();

            // Amazon Order Details
            $table->string('amazon_order_id')->unique();
            $table->string('marketplace_id')->nullable();
            $table->string('sales_channel')->nullable();

            // Order Status
            $table->string('order_status')->index();
            $table->string('order_type')->nullable();

            // Dates
            $table->dateTime('purchase_date')->nullable()->index();
            $table->dateTime('last_update_date')->nullable();
            $table->dateTime('earliest_ship_date')->nullable();
            $table->dateTime('latest_ship_date')->nullable();
            $table->dateTime('earliest_delivery_date')->nullable();
            $table->dateTime('latest_delivery_date')->nullable();

            // Fulfillment
            $table->string('fulfillment_channel')->nullable();
            $table->string('shipment_service_level_category')->nullable();
            $table->string('ship_service_level')->nullable();
            $table->string('easy_ship_shipment_status')->nullable();

            // Payment
            $table->string('payment_method')->nullable();
            $table->decimal('order_total', 12, 2)->default(0);
            $table->string('currency', 10)->default('INR');

            // Item Counts
            $table->unsignedInteger('number_of_items_shipped')->default(0);
            $table->unsignedInteger('number_of_items_unshipped')->default(0);

            // Flags
            $table->boolean('is_prime')->default(false);
            $table->boolean('is_premium_order')->default(false);
            $table->boolean('is_business_order')->default(false);
            $table->boolean('is_replacement_order')->default(false);
            $table->boolean('is_access_point_order')->default(false);
            $table->boolean('is_global_express_enabled')->default(false);
            $table->boolean('is_ispu')->default(false);
            $table->boolean('has_regulated_items')->default(false);

            // Customer Shipping Address
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();

            // Warehouse Address
            $table->string('ship_from_city')->nullable();
            $table->string('ship_from_state')->nullable();
            $table->string('ship_from_postal_code')->nullable();
            $table->string('ship_from_country')->nullable();

            // Sync Info
            $table->timestamp('synced_at')->nullable();

            // Store Full Amazon Response
            $table->json('raw_response')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('marketplace_id');
            $table->index('payment_method');
            $table->index('fulfillment_channel');
            $table->index('easy_ship_shipment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_orders');
    }
};