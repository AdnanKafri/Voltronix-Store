<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'auto_delivery_enabled')) {
                $table->boolean('auto_delivery_enabled')->default(false)->after('delivery_instructions');
            }

            if (!Schema::hasColumn('products', 'delivery_file_path')) {
                $table->string('delivery_file_path', 500)->nullable()->after('delivery_config');
            }

            if (!Schema::hasColumn('products', 'delivery_file_name')) {
                $table->string('delivery_file_name')->nullable()->after('delivery_file_path');
            }

            if (!Schema::hasColumn('products', 'default_expiration_days')) {
                $table->integer('default_expiration_days')->nullable()->after('delivery_file_name');
            }

            if (!Schema::hasColumn('products', 'default_max_downloads')) {
                $table->integer('default_max_downloads')->nullable()->after('default_expiration_days');
            }

            if (!Schema::hasColumn('products', 'default_max_views')) {
                $table->integer('default_max_views')->nullable()->after('default_max_downloads');
            }
        });

        $existingIndex = DB::select("SHOW INDEX FROM products WHERE Key_name = 'idx_products_auto_delivery'");

        if (
            empty($existingIndex)
            && Schema::hasColumn('products', 'auto_delivery_enabled')
            && Schema::hasColumn('products', 'delivery_type')
        ) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['auto_delivery_enabled', 'delivery_type'], 'idx_products_auto_delivery');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left as a no-op because these automation fields are now
        // part of the canonical products schema for fresh installs.
    }
};
