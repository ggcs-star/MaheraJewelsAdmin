<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('price');
            $table->enum('discount_type', ['percent', 'flat'])
                  ->default('percent')
                  ->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'discount_type']);
        });
    }
};
