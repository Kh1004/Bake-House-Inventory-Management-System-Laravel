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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('ingredients', 'unit_of_measure')) {
                $table->string('unit_of_measure')->default('kg')->after('description');
            }
            if (!Schema::hasColumn('ingredients', 'current_quantity')) {
                $table->decimal('current_quantity', 10, 2)->default(0)->after('unit_of_measure');
            }
            if (!Schema::hasColumn('ingredients', 'reorder_level')) {
                $table->decimal('reorder_level', 10, 2)->default(10)->after('current_quantity');
            }
            if (!Schema::hasColumn('ingredients', 'cost_per_unit')) {
                $table->decimal('cost_per_unit', 10, 2)->default(0)->after('reorder_level');
            }
            if (!Schema::hasColumn('ingredients', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('cost_per_unit');
            }
            if (!Schema::hasColumn('ingredients', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('expiry_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop columns in the down migration to prevent data loss
        // If you need to rollback, create a new migration
    }
};
