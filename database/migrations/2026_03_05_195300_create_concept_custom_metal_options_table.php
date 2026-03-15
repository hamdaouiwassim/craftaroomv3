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
        Schema::create('concept_custom_metal_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metal_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('ref')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index(['concept_id', 'metal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept_custom_metal_options');
    }
};
