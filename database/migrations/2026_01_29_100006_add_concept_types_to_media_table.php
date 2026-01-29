<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE media MODIFY COLUMN type ENUM('avatar', 'product', 'threedmodel', 'material', 'category', 'concept', 'concept_threedmodel', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE media MODIFY COLUMN type ENUM('avatar', 'product', 'threedmodel', 'material', 'category', 'other') DEFAULT 'other'");
    }
};
