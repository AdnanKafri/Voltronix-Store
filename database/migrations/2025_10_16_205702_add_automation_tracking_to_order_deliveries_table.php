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
        Schema::table('order_deliveries', function (Blueprint $table) {
            $table->boolean('created_automatically')->default(false)->after('admin_notes');
            $table->string('automation_source', 100)->nullable()->after('created_automatically');
            
            // Index for filtering automated deliveries
            $table->index(['created_automatically', 'automation_source'], 'idx_deliveries_automation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_deliveries', function (Blueprint $table) {
            $table->dropIndex('idx_deliveries_automation');
            $table->dropColumn(['created_automatically', 'automation_source']);
        });
    }
};
