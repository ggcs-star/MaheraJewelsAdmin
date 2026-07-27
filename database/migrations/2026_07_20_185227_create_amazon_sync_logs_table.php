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
        Schema::create('amazon_sync_logs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Module
            |--------------------------------------------------------------------------
            */

            $table->enum('module', [
                'inventory',
                'orders',
                'order_items',
                'auth'
            ]);

            /*
            |--------------------------------------------------------------------------
            | API
            |--------------------------------------------------------------------------
            */

            $table->string('api_name');

            $table->string('http_method')->default('GET');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'success',
                'failed',
                'warning'
            ])->default('success');

            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            $table->text('message')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request / Response
            |--------------------------------------------------------------------------
            */

            $table->longText('request_payload')->nullable();

            $table->longText('response_payload')->nullable();

            /*
            |--------------------------------------------------------------------------
            | HTTP Response
            |--------------------------------------------------------------------------
            */

            $table->integer('http_status')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Exception
            |--------------------------------------------------------------------------
            */

            $table->text('exception')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Execution Time
            |--------------------------------------------------------------------------
            */

            $table->decimal('execution_time',10,2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sync Time
            |--------------------------------------------------------------------------
            */

            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('module');
            $table->index('status');
            $table->index('api_name');
            $table->index('http_status');
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_sync_logs');
    }
};