<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop the foreign key constraint
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Change the enum type to include more values
        DB::statement("ALTER TABLE alerts MODIFY COLUMN type ENUM('info', 'warning', 'danger', 'success', 'reminder', 'low_stock', 'expiry_alert', 'price_change') NOT NULL DEFAULT 'info'");

        // Re-add the foreign key
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Revert to the original enum values
        DB::statement("ALTER TABLE alerts MODIFY COLUMN type ENUM('info', 'warning', 'danger', 'success') NOT NULL DEFAULT 'info'");

        // Re-add the foreign key
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
