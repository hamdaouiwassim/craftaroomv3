<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE construction_requests MODIFY COLUMN status ENUM('draft', 'pending', 'accepted', 'declined', 'completed', 'canceled') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        DB::statement("UPDATE construction_requests SET status = 'pending' WHERE status = 'draft'");
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE construction_requests MODIFY COLUMN status ENUM('pending', 'accepted', 'declined', 'completed', 'canceled') DEFAULT 'pending'");
        }
    }
};
