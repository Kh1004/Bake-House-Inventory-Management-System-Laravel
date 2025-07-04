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
        // Add guard_name column if it doesn't exist
        if (!Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name')->default('web')->after('name');
            });
        }

        // Make description nullable if it exists
        if (Schema::hasColumn('roles', 'description')) {
            // Use raw SQL to modify the column to be nullable
            DB::statement("ALTER TABLE `roles` MODIFY COLUMN `description` VARCHAR(255) NULL");
        } else {
            // If description doesn't exist, add it as nullable
            Schema::table('roles', function (Blueprint $table) {
                $table->string('description')->nullable()->after('name');
            });
        }

        // Add unique index for name and guard_name combination
        // We'll use a try-catch to handle the case where the index might already exist
        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
            });
        } catch (\Exception $e) {
            // Index might already exist, we can ignore this error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the unique index if it exists
        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique('roles_name_guard_name_unique');
            });
        } catch (\Exception $e) {
            // Index might not exist, we can ignore this error
        }

        // We won't remove the guard_name or description columns in the down method
        // to prevent data loss in case of rollback
    }
};
