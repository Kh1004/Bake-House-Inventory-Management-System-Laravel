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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['ingredient_id']);
            
            // Recreate the foreign key with onDelete('cascade')
            $table->foreign('ingredient_id')
                  ->references('id')
                  ->on('ingredients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Drop the cascade foreign key
            $table->dropForeign(['ingredient_id']);
            
            // Recreate the original foreign key without cascade
            $table->foreign('ingredient_id')
                  ->references('id')
                  ->on('ingredients');
        });
    }
};
