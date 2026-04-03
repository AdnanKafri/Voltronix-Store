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
            $table->boolean('is_featured')->default(false)->after('status');
            $table->boolean('is_new')->default(false)->after('is_featured');
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            
            // Add indexes for performance
            $table->index(['is_featured', 'status']);
            $table->index(['is_new', 'status']);
            $table->index(['discount_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_featured', 'status']);
            $table->dropIndex(['is_new', 'status']);
            $table->dropIndex(['discount_price']);
            
            $table->dropColumn(['is_featured', 'is_new', 'discount_price']);
        });
    }
};
