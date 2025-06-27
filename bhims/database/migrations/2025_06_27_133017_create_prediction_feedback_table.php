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
        Schema::create('prediction_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('prediction_method');
            $table->date('prediction_date');
            $table->json('prediction_data');
            $table->json('actual_data')->nullable();
            $table->float('accuracy_rating')->nullable();
            $table->text('user_notes')->nullable();
            $table->timestamps();
            
            // Using a shorter index name to avoid 'identifier name too long' error
            $table->index(
                ['product_id', 'prediction_date', 'prediction_method'],
                'pred_feedback_composite_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_feedback');
    }
};
