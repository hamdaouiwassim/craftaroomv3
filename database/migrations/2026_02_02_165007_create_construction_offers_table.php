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
        Schema::create('construction_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_request_id')->constrained('construction_requests')->onDelete('cascade');
            $table->foreignId('constructor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('MAD');
            $table->integer('construction_time_days');
            $table->text('offer_details')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_offers');
    }
};
