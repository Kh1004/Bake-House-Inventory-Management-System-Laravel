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
        Schema::table('ingredients', function (Blueprint $table) {
            if (!Schema::hasColumn('ingredients', 'low_stock_notified')) {
                $table->boolean('low_stock_notified')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('ingredients', 'last_stock_notification_at')) {
                $table->timestamp('last_stock_notification_at')->nullable()->after('low_stock_notified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            //
        });
    }
};
