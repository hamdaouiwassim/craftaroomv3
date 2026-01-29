<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * source: designer = created by designers, library = added by administration.
     */
    public function up(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->enum('source', ['designer', 'library'])->default('designer')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
