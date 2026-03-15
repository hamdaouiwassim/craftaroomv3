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
            $table->enum('style_type', ['standard', 'artisant'])->default('standard')->after('category_id');
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->enum('style_type', ['standard', 'artisant'])->default('standard')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('style_type');
        });

        Schema::table('concepts', function (Blueprint $table) {
            $table->dropColumn('style_type');
        });
    }
};
