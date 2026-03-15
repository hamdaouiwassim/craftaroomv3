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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_resizable')->default(false);
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->boolean('is_resizable')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_resizable');
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('is_resizable');
        });
    }
};
