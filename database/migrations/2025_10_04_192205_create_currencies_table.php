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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Bilingual names (EN/AR)
            $table->string('code', 3)->unique(); // USD, SAR, SYP
            $table->string('symbol', 10); // $, ﷼, ل.س
            $table->decimal('exchange_rate', 15, 8)->default(1.00000000); // Exchange rate relative to base currency
            $table->boolean('is_default')->default(false); // Only one can be default
            $table->boolean('is_active')->default(true); // Active/Inactive status
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['is_active', 'is_default']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
