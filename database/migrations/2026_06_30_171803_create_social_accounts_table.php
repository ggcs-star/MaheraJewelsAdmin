<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('platform')->default('instagram');

            $table->string('facebook_user_id')->nullable();

            $table->string('page_id')->nullable();

            $table->string('instagram_business_id')->nullable();

            $table->string('instagram_username')->nullable();

            $table->longText('access_token');

            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('instagram_business_id');
            $table->index('facebook_user_id');
            $table->index('page_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};