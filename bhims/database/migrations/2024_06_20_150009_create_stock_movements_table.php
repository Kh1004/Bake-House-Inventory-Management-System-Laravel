<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingredient_id');
            $table->decimal('quantity', 10, 2);
            $table->string('movement_type'); // 'purchase', 'consumption', 'adjustment', 'waste', etc.
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reference_id')->nullable(); // Can reference PO, recipe, etc.
            $table->string('reference_type')->nullable(); // e.g., 'App\Models\PurchaseOrder', 'App\Models\Recipe'
            $table->timestamps();

            $table->foreign('ingredient_id')->references('id')->on('ingredients');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};
