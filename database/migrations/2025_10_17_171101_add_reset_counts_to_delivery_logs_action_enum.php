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
        // Add missing enum values to delivery_logs action column
        DB::statement("ALTER TABLE delivery_logs MODIFY COLUMN action ENUM(
            'download', 'view_credentials', 'reveal_credentials', 
            'reissue', 'extend', 'revoke', 'regenerate_token',
            'access_denied', 'expired_access', 'limit_exceeded',
            'reset_counts', 'access_request'
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE delivery_logs MODIFY COLUMN action ENUM(
            'download', 'view_credentials', 'reveal_credentials', 
            'reissue', 'extend', 'revoke', 'regenerate_token',
            'access_denied', 'expired_access', 'limit_exceeded'
        )");
    }
};
