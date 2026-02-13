<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
    $table->id();

    $table->string('invoice_number')->unique();
    $table->date('invoice_date');

    Schema::table('invoices', function (Blueprint $table) {
    // sirf foreign key add karo, column already exists
    $table->foreign('organization_id')
          ->references('id')
          ->on('organizations')
          ->nullOnDelete();
});


    $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
    $table->string('customer_name')->nullable();
    $table->string('customer_mobile', 20)->nullable();
    $table->text('customer_address')->nullable();

    $table->decimal('sub_total', 10, 2)->default(0);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('tax_amount', 10, 2)->default(0);
    $table->decimal('grand_total', 10, 2)->default(0);

    $table->enum('payment_type', ['cash', 'bank'])->default('cash');
    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('due_amount', 10, 2)->default(0);

    $table->enum('status', ['draft', 'completed', 'cancelled'])
          ->default('completed');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('invoices');
    }
};
