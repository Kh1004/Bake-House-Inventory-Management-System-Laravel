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
            $table->string('unit_of_measure')->default('kg')->after('description');
            $table->decimal('current_quantity', 10, 2)->default(0)->after('unit_of_measure');
            $table->decimal('reorder_level', 10, 2)->default(10)->after('current_quantity');
            $table->decimal('cost_per_unit', 10, 2)->default(0)->after('reorder_level');
            $table->date('expiry_date')->nullable()->after('cost_per_unit');
            $table->string('batch_number')->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn([
                'unit_of_measure',
                'current_quantity',
                'reorder_level',
                'cost_per_unit',
                'expiry_date',
                'batch_number'
            ]);
        });
    }
};
