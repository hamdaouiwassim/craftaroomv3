<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('construction_requests', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('status');
        });

        DB::table('construction_requests')
            ->where('status', '!=', 'draft')
            ->whereNull('submitted_at')
            ->update(['submitted_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('construction_requests', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
};
