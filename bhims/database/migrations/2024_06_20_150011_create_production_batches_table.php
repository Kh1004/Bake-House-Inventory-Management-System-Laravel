<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('user_id');
            $table->date('production_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_produced', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->timestamps();

            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_batches');
    }
};
