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
        // Fix the delivery_type ENUM to include 'manual' and make it nullable
        DB::statement("ALTER TABLE products MODIFY COLUMN delivery_type ENUM('manual', 'file', 'credentials', 'license', 'service') NULL DEFAULT 'manual'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM (without manual)
        DB::statement("ALTER TABLE products MODIFY COLUMN delivery_type ENUM('file', 'credentials', 'license', 'service') NOT NULL DEFAULT 'file'");
    }
};
