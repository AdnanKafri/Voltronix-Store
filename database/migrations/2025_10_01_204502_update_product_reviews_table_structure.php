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
        Schema::table('product_reviews', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('product_reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('comment');
            }
            
            if (!Schema::hasColumn('product_reviews', 'admin_reply_by')) {
                $table->foreignId('admin_reply_by')->nullable()->constrained('users')->onDelete('set null')->after('admin_reply_at');
            }
            
            if (!Schema::hasColumn('product_reviews', 'is_verified_purchase')) {
                $table->boolean('is_verified_purchase')->default(false)->after('admin_reply_by');
            }
            
            // Remove old columns if they exist
            if (Schema::hasColumn('product_reviews', 'approved')) {
                $table->dropColumn('approved');
            }
            
            if (Schema::hasColumn('product_reviews', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            
            if (Schema::hasColumn('product_reviews', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            
            // Add indexes if they don't exist
            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']);
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_reply_by', 'is_verified_purchase']);
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
