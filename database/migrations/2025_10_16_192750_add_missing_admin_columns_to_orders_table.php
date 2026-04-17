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
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('admins')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('admins')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'downloads_enabled')) {
                $table->boolean('downloads_enabled')->default(false)->after('admin_notes');
            }

            if (!Schema::hasColumn('orders', 'downloads_expires_at')) {
                $table->timestamp('downloads_expires_at')->nullable()->after('downloads_enabled');
            }

            if (!Schema::hasColumn('orders', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left as a no-op because the canonical orders schema
        // now includes these columns in the base table migration.
    }
};
