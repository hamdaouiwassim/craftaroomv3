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
        Schema::create('concept_weights', function (Blueprint $table) {
            $table->id();
            $table->float('weight_value');
            $table->enum('weight_unit', ['KG', 'LB']);
            $table->foreignId('concept_measure_id')->constrained('concept_measures')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept_weights');
    }
};
