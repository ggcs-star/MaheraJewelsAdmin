<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_tokens', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->longText('fcm_token');

            $table->string('browser')->nullable();

            $table->string('platform')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->timestamp('last_seen')->nullable();

            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_tokens');
    }
};