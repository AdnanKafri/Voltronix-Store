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

        if (!Schema::hasColumn('orders', 'payment_method') || !Schema::hasColumn('orders', 'payment_details')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->enum('payment_method', ['bank_transfer', 'crypto_usdt', 'crypto_btc', 'mtn_cash', 'syriatel_cash'])
                        ->nullable()
                        ->after('status');
                }

                if (!Schema::hasColumn('orders', 'payment_details')) {
                    $table->json('payment_details')->nullable()->after('payment_method');
                }
            });
        }

        if (Schema::hasColumn('orders', 'receipt_path') && !Schema::hasColumn('orders', 'payment_proof_path')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('receipt_path', 'payment_proof_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left as a no-op because the current base orders table
        // already includes the canonical payment columns.
    }
};
