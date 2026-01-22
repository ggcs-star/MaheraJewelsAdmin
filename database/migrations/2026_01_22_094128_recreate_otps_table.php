<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::dropIfExists('otps');

    Schema::create('otps', function (Blueprint $table) {
        $table->id();
        $table->string('email')->index();
        $table->string('code');
        $table->string('type');
        $table->timestamp('expires_at');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('otps');
}

};
