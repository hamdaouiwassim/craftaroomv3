<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores designer's chosen sub-metal (metal_option_id) per metal for each concept.
     */
    public function up(): void
    {
        Schema::create('concept_metal_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metal_option_id')->constrained()->cascadeOnDelete();
            $table->unique(['concept_id', 'metal_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept_metal_option');
    }
};
