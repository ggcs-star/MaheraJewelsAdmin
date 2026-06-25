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
    Schema::create('user_activity_logs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('activity_type');      // login, logout, order, payment
        $table->unsignedBigInteger('reference_id')->nullable();

        $table->ipAddress('ip_address')->nullable();

        $table->string('country')->nullable();
        $table->string('state')->nullable();
        $table->string('city')->nullable();

        $table->string('device')->nullable();
        $table->string('browser')->nullable();
        $table->string('platform')->nullable();

        $table->text('user_agent')->nullable();

        $table->string('status')->nullable(); // success, failed

        $table->json('meta')->nullable();     // extra data

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
