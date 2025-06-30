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
        Schema::create('alert_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('alert_type'); // e.g., 'low_stock', 'price_change', 'demand_spike'
            $table->json('channels')->default(json_encode(['email' => true, 'sms' => false, 'in_app' => true]));
            $table->json('thresholds')->nullable(); // JSON field for different threshold values
            $table->boolean('is_active')->default(true);
            $table->text('custom_message')->nullable();
            $table->json('preferences')->nullable(); // Additional preferences in JSON format
            $table->timestamps();
            
            // Add index for faster lookups
            $table->index(['user_id', 'alert_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_configurations');
    }
};
