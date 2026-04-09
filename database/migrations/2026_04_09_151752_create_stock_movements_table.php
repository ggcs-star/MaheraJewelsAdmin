<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('product_id');

            $table->unsignedBigInteger('variant_id')->nullable();

            $table->unsignedBigInteger('platform_id')->nullable();

            $table->enum('movement', ['in', 'out']);

            $table->integer('quantity');

            $table->integer('balance');

            $table->string('reference_type');

            $table->unsignedBigInteger('reference_id');

            $table->string('remarks')->nullable();

            $table->timestamps();

            $table->index('product_id');

            $table->index('variant_id');

            $table->index('platform_id');

            $table->index('reference_type');

            $table->index('reference_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};