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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name'); // Translatable name field
            $table->json('description')->nullable(); // Translatable description field
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // Discount value (percentage or fixed amount)
            $table->decimal('min_order_value', 10, 2)->nullable(); // Minimum order value required
            $table->decimal('max_discount', 10, 2)->nullable(); // Maximum discount for percentage coupons
            $table->integer('usage_limit')->nullable(); // Global usage limit
            $table->integer('per_user_limit')->default(1); // Per user usage limit
            $table->integer('used_count')->default(0); // Track total usage
            $table->datetime('start_date')->nullable(); // When coupon becomes active
            $table->datetime('expiry_date')->nullable(); // When coupon expires
            $table->foreignId('target_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Restrict to specific user
            $table->boolean('first_time_only')->default(false); // Restrict to first-time customers
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['code', 'is_active']);
            $table->index(['is_active', 'start_date', 'expiry_date']);
            $table->index('target_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
