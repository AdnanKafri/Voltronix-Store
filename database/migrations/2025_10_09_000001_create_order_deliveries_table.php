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
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Delivery Type and Content
            $table->enum('type', ['file', 'credentials', 'license', 'service']);
            $table->string('title'); // Delivery title/name
            $table->text('description')->nullable();
            
            // File Delivery
            $table->string('file_path')->nullable(); // Path to file in private storage
            $table->string('file_name')->nullable(); // Original filename
            $table->string('file_type')->nullable(); // MIME type
            $table->bigInteger('file_size')->nullable(); // File size in bytes
            
            // Credentials Delivery (encrypted)
            $table->text('encrypted_credentials')->nullable(); // JSON encrypted credentials
            $table->string('credentials_type')->nullable(); // email_password, license_key, api_key, etc.
            
            // Access Control
            $table->string('token')->unique(); // Unique access token
            $table->timestamp('expires_at')->nullable(); // Expiration date
            $table->integer('max_downloads')->nullable(); // Max download attempts (null = unlimited)
            $table->integer('downloads_count')->default(0); // Current download count
            $table->integer('max_views')->nullable(); // Max credential views (null = unlimited)
            $table->integer('views_count')->default(0); // Current view count
            $table->boolean('revoked')->default(false); // Manual revocation
            
            // Security Settings
            $table->boolean('require_otp')->default(false); // Require OTP for access
            $table->integer('view_duration')->nullable(); // Seconds credentials are visible
            $table->json('allowed_ips')->nullable(); // IP whitelist
            
            // Tracking
            $table->timestamp('first_accessed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('access_log')->nullable(); // Detailed access history
            
            // Admin Management
            $table->foreignId('created_by')->nullable()->constrained('users'); // Admin who created delivery
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Admin who last updated
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['order_id', 'user_id']);
            $table->index(['token', 'revoked']);
            $table->index(['expires_at', 'revoked']);
            $table->index(['type', 'revoked']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_deliveries');
    }
};
