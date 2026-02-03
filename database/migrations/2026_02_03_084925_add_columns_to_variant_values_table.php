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
            //
    Schema::table('variant_values', function (Blueprint $table) {
    $table->foreignId('variant_id')
          ->constrained('variants')
          ->cascadeOnDelete();

    $table->string('value');
    $table->string('extra')->nullable();
});

    }

   public function down(): void
{
    Schema::table('variant_values', function (Blueprint $table) {
        $table->dropForeign(['variant_id']);
        $table->dropColumn(['variant_id', 'value', 'extra']);
    });
}

};
