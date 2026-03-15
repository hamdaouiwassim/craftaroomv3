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
        Schema::table('construction_requests', function (Blueprint $table) {
            $table->enum('request_type', ['concept', 'product'])->default('concept')->after('id');
            $table->foreignId('product_id')->nullable()->after('concept_id')->constrained('products')->nullOnDelete();
            $table->foreignId('target_producer_id')->nullable()->after('customer_id')->constrained('users')->nullOnDelete();
            $table->longText('viewer_state_json')->nullable()->after('customer_notes');
            $table->json('requested_dimensions_json')->nullable()->after('viewer_state_json');

            $table->index(['request_type', 'target_producer_id']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('construction_requests', function (Blueprint $table) {
            $table->dropIndex(['request_type', 'target_producer_id']);
            $table->dropIndex(['product_id']);

            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('target_producer_id');
            $table->dropColumn([
                'request_type',
                'viewer_state_json',
                'requested_dimensions_json',
            ]);
        });
    }
};
