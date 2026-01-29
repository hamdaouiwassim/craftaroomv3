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
        Schema::table('metals', function (Blueprint $table) {
            $table->string('ref')->nullable()->after('id');
            $table->string('image_url')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metals', function (Blueprint $table) {
            $table->dropColumn(['ref', 'image_url']);
        });
    }
};
