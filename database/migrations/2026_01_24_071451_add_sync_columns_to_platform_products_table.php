<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('platform_products', function (Blueprint $table) {
            $table->string('platform_product_id')->nullable()->after('platform_stock');
            $table->string('platform_listing_id')->nullable()->after('platform_product_id');
            $table->text('platform_url')->nullable()->after('platform_listing_id');

            $table->enum('sync_status', ['synced', 'pending', 'failed'])->default('pending')->after('status');
            $table->timestamp('last_synced_at')->nullable()->after('sync_status');
            $table->text('error_message')->nullable()->after('last_synced_at');

            $table->boolean('is_enabled')->default(true)->after('error_message');
        });
    }

    public function down(): void
    {
        Schema::table('platform_products', function (Blueprint $table) {
            $table->dropColumn([
                'platform_product_id',
                'platform_listing_id',
                'platform_url',
                'sync_status',
                'last_synced_at',
                'error_message',
                'is_enabled',
            ]);
        });
    }
};
