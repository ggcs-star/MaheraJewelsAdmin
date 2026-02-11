<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
              ->constrained('orders')
              ->cascadeOnDelete();

        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('variant_id')->nullable();

        $table->string('product_name');
        $table->decimal('price', 10, 2);
        $table->integer('quantity');
        $table->decimal('subtotal', 10, 2);

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
