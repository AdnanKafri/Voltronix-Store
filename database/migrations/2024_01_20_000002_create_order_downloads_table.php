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
        Schema::create('order_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Download Details
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->bigInteger('file_size')->nullable();
            $table->string('download_token')->unique();
            
            // Access Control
            $table->integer('download_count')->default(0);
            $table->integer('download_limit')->nullable(); // null = unlimited
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Tracking
            $table->timestamp('first_downloaded_at')->nullable();
            $table->timestamp('last_downloaded_at')->nullable();
            $table->json('download_ips')->nullable(); // Track download IPs for security
            
            $table->timestamps();
            
            // Indexes
            $table->index(['order_id', 'user_id']);
            $table->index(['download_token', 'is_active']);
            $table->index(['expires_at', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_downloads');
    }
};
