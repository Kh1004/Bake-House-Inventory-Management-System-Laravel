<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->decimal('quantity', 10, 2);
            $table->string('unit_of_measure');
            $table->text('notes')->nullable();
            
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredient_recipe');
    }
};
