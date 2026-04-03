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
        Schema::table('products', function (Blueprint $table) {
            // Only add fields that don't exist yet
            // auto_delivery_enabled already exists
            // delivery_type already exists 
            // delivery_config already exists
            
            // File Delivery Configuration (new fields)
            $table->string('delivery_file_path', 500)->nullable()->after('delivery_config');
            $table->string('delivery_file_name')->nullable()->after('delivery_file_path');
            
            // Default Automation Settings (new fields)
            $table->integer('default_expiration_days')->nullable()->after('delivery_file_name');
            $table->integer('default_max_downloads')->nullable()->after('default_expiration_days');
            $table->integer('default_max_views')->nullable()->after('default_max_downloads');
            
            // Indexes for performance
            $table->index(['auto_delivery_enabled', 'delivery_type'], 'idx_products_auto_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_auto_delivery');
            
            $table->dropColumn([
                'delivery_file_path',
                'delivery_file_name',
                'default_expiration_days',
                'default_max_downloads',
                'default_max_views'
            ]);
        });
    }
};
