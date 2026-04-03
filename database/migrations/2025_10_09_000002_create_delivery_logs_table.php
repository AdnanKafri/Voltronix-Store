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
        Schema::create('delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('order_deliveries')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Action Details
            $table->enum('action', [
                'download', 'view_credentials', 'reveal_credentials', 
                'reissue', 'extend', 'revoke', 'regenerate_token',
                'access_denied', 'expired_access', 'limit_exceeded'
            ]);
            $table->string('status')->default('success'); // success, failed, denied
            $table->text('details')->nullable(); // Additional action details
            
            // Request Information
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable(); // Geo-location
            $table->string('city')->nullable();
            
            // Security Information
            $table->boolean('suspicious')->default(false); // Flagged as suspicious
            $table->text('security_notes')->nullable();
            $table->string('session_id')->nullable();
            
            // Performance Tracking
            $table->integer('response_time')->nullable(); // Response time in milliseconds
            $table->bigInteger('bytes_transferred')->nullable(); // For downloads
            
            $table->timestamp('created_at');
            
            // Indexes for analytics and security
            $table->index(['delivery_id', 'created_at']);
            $table->index(['user_id', 'action', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['suspicious', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_logs');
    }
};
