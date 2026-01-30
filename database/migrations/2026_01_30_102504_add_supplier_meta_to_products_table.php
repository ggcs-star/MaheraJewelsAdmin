<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('warehouse_id')
                ->nullable()
                ->after('supplier_id')
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->date('expected_delivery_date')
                ->nullable()
                ->after('warehouse_id');

            $table->string('payment_terms')
                ->nullable()
                ->after('expected_delivery_date');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn([
                'warehouse_id',
                'expected_delivery_date',
                'payment_terms',
            ]);
        });
    }
};
