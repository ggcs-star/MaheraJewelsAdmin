<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('reels', 'comments_count')) {
            Schema::table('reels', function (Blueprint $table) {
                $table->integer('comments_count')->default(0)->after('shares_count');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reels', 'comments_count')) {
            Schema::table('reels', function (Blueprint $table) {
                $table->dropColumn('comments_count');
            });
        }
    }
};