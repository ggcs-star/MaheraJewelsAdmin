<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('email');

            $table->boolean('receive_order')->default(true);
            $table->boolean('receive_cancel')->default(false);
            $table->boolean('receive_registration')->default(false);
            $table->boolean('receive_contact')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};