<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('invoice_name')->nullable()->after('name');
            $table->string('invoice_logo')->nullable()->after('logo_path');  // ✅ 'logo_path' ke baad
            $table->string('invoice_email')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['invoice_name', 'invoice_logo', 'invoice_email']);
        });
    }
};