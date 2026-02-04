<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('variant_values', function (Blueprint $table) {
            $table->string('color')->nullable()->after('value');
            $table->integer('height')->nullable()->after('color');
            $table->integer('width')->nullable()->after('height');
            $table->boolean('is_active')->default(1)->after('width');

            // remove extra
            $table->dropColumn('extra');
        });
    }

    public function down()
    {
        Schema::table('variant_values', function (Blueprint $table) {
            $table->dropColumn(['color', 'height', 'width', 'is_active']);
            $table->string('extra')->nullable();
        });
    }
};
