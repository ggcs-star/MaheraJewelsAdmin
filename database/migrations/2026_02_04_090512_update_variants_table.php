<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->boolean('has_dimensions')
                  ->default(false)
                  ->after('input_type');
        });
    }

    public function down()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn('has_dimensions');
        });
    }
};
