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
        Schema::create('competitor_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('competitor_name');
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('LKR');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->date('analysis_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['competitor_name', 'product_name']);
            $table->index('analysis_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitor_analysis');
    }
};
