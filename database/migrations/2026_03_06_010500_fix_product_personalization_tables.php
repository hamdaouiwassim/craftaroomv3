<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('product_metal_options')) {
            Schema::table('product_metal_options', function (Blueprint $table) {
                if (!Schema::hasColumn('product_metal_options', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('product_metal_options', 'metal_id')) {
                    $table->foreignId('metal_id')->nullable()->after('product_id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('product_metal_options', 'metal_option_id')) {
                    $table->foreignId('metal_option_id')->nullable()->after('metal_id')->constrained()->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('product_custom_metal_options')) {
            Schema::table('product_custom_metal_options', function (Blueprint $table) {
                if (!Schema::hasColumn('product_custom_metal_options', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('product_custom_metal_options', 'metal_id')) {
                    $table->foreignId('metal_id')->nullable()->after('product_id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('product_custom_metal_options', 'name')) {
                    $table->string('name')->nullable()->after('metal_id');
                }
                if (!Schema::hasColumn('product_custom_metal_options', 'ref')) {
                    $table->string('ref')->nullable()->after('name');
                }
                if (!Schema::hasColumn('product_custom_metal_options', 'image_url')) {
                    $table->string('image_url')->nullable()->after('ref');
                }
            });
        }
    }

    public function down(): void
    {
        // Keep no-op to avoid destructive rollback on production data.
    }
};
