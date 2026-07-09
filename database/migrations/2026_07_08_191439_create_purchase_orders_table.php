<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {

            $table->id();

            $table->string('po_number')->unique();

            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();

            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();

            $table->date('order_date');

            $table->date('expected_delivery_date')->nullable();

            $table->string('invoice_number')->nullable();

            $table->string('invoice_file')->nullable();

            $table->string('payment_terms')->nullable();

            $table->text('notes')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('tax_amount', 12, 2)->default(0);

            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->decimal('grand_total', 12, 2)->default(0);

            $table->enum('status', [
                'draft',
                'ordered',
                'received',
                'cancelled'
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};