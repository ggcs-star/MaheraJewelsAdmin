<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
    $table->id();

    $table->string('name');
    $table->string('email')->nullable()->index();
    $table->string('mobile', 20)->nullable()->index();

    $table->string('address_line_1')->nullable();
    $table->string('address_line_2')->nullable();
    $table->string('city', 100)->nullable();
    $table->string('state', 100)->nullable();
    $table->string('country', 100)->default('India');
    $table->string('zip_code', 20)->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamps();
});

    }
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
