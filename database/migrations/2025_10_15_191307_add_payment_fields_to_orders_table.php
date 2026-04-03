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
            // Add missing payment fields
            $table->string('payment_method')->nullable()->after('status');
            $table->json('payment_details')->nullable()->after('payment_method');
            
            // Rename receipt_path to payment_proof_path for consistency
            $table->renameColumn('receipt_path', 'payment_proof_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['payment_method', 'payment_details']);
            
            // Rename back to original
            $table->renameColumn('payment_proof_path', 'receipt_path');
        });
    }
};
