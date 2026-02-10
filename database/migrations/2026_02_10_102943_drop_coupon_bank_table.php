<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::dropIfExists('coupon_bank');
}

public function down()
{
    Schema::create('coupon_bank', function (Blueprint $table) {
        $table->id();
        $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
        $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
        $table->string('card_type')->nullable();
        $table->timestamps();
    });
}

};
