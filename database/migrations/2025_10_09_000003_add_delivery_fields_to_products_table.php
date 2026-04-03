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
            // Delivery Configuration
            $table->enum('delivery_type', ['file', 'credentials', 'license', 'service'])->default('file')->after('status');
            $table->json('delivery_config')->nullable()->after('delivery_type'); // Delivery-specific configuration
            $table->integer('default_download_limit')->nullable()->after('delivery_config'); // Default download limit for files
            $table->integer('default_access_days')->default(7)->after('default_download_limit'); // Default access duration in days
            $table->boolean('requires_otp')->default(false)->after('default_access_days'); // Require OTP for access
            $table->text('delivery_instructions')->nullable()->after('requires_otp'); // Instructions for customers
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_type',
                'delivery_config',
                'default_download_limit',
                'default_access_days',
                'requires_otp',
                'delivery_instructions'
            ]);
        });
    }
};
