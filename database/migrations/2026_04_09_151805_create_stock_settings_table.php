<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('threshold')->default(5);
            $table->string('admin_email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_settings');
    }
};