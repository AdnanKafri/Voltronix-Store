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
        Schema::table('orders', function (Blueprint $table) {
            // Add missing admin columns that should have been in the original migration
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('admins')->onDelete('set null');
            $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('admins')->onDelete('set null');
            
            // Add missing columns for download management
            $table->boolean('downloads_enabled')->default(false)->after('admin_notes');
            $table->timestamp('downloads_expires_at')->nullable()->after('downloads_enabled');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            // Drop the columns
            $table->dropColumn([
                'approved_by',
                'rejected_by', 
                'downloads_enabled',
                'downloads_expires_at',
                'rejection_reason'
            ]);
        });
    }
};
