<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow multiple metal_option choices per metal: drop one-option-per-metal unique,
     * add unique(concept_id, metal_id, metal_option_id) to avoid duplicate rows.
     * FKs must be dropped first because MySQL uses the unique index for them.
     */
    public function up(): void
    {
        Schema::table('concept_metal_option', function (Blueprint $table) {
            $table->dropForeign(['concept_id']);
            $table->dropForeign(['metal_id']);
            $table->dropForeign(['metal_option_id']);
            $table->dropUnique(['concept_id', 'metal_id']);
            $table->unique(['concept_id', 'metal_id', 'metal_option_id']);
            $table->foreign('concept_id')->references('id')->on('concepts')->cascadeOnDelete();
            $table->foreign('metal_id')->references('id')->on('metals')->cascadeOnDelete();
            $table->foreign('metal_option_id')->references('id')->on('metal_options')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concept_metal_option', function (Blueprint $table) {
            $table->dropForeign(['concept_id']);
            $table->dropForeign(['metal_id']);
            $table->dropForeign(['metal_option_id']);
            $table->dropUnique(['concept_id', 'metal_id', 'metal_option_id']);
            $table->unique(['concept_id', 'metal_id']);
            $table->foreign('concept_id')->references('id')->on('concepts')->cascadeOnDelete();
            $table->foreign('metal_id')->references('id')->on('metals')->cascadeOnDelete();
            $table->foreign('metal_option_id')->references('id')->on('metal_options')->cascadeOnDelete();
        });
    }
};
