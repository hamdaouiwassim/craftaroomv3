<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reel_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reelable_type');
            $table->unsignedBigInteger('reelable_id');
            $table->text('comment');
            $table->timestamps();

            $table->index(['reelable_type', 'reelable_id'], 'reel_comments_reelable_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reel_comments');
    }
};
