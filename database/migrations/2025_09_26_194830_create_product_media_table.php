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
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image', 'video', 'before', 'after', 'youtube']);
            $table->string('path')->nullable(); // For uploaded files
            $table->string('url')->nullable(); // For external URLs (YouTube, etc.)
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data (dimensions, duration, etc.)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'type', 'sort_order']);
            $table->index(['product_id', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
