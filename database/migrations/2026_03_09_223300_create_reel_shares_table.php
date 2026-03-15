<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reel_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reelable_type');
            $table->unsignedBigInteger('reelable_id');
            $table->timestamps();

            $table->index(['reelable_type', 'reelable_id'], 'reel_shares_reelable_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reel_shares');
    }
};
