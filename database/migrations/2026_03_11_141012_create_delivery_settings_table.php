<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();

            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('platform_fee', 8, 2)->default(0);

            $table->decimal('tax_percent', 5, 2)->default(0);

            $table->decimal('free_delivery_above', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};