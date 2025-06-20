<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('recipe_id')->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
