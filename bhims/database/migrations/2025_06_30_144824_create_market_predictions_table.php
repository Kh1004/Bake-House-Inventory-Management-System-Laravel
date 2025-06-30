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
        Schema::create('market_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('prediction_date');
            $table->decimal('predicted_demand', 10, 2);
            $table->decimal('confidence_interval_lower', 10, 2);
            $table->decimal('confidence_interval_upper', 10, 2);
            $table->json('historical_data')->nullable();
            $table->json('prediction_metrics')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'prediction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_predictions');
    }
};
