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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->json('name'); // Translatable name field
            $table->string('slug')->unique();
            $table->json('description')->nullable(); // Translatable description field
            $table->decimal('price', 10, 2);
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->string('thumbnail')->nullable(); // Product thumbnail image
            $table->json('features')->nullable(); // Additional product features (translatable)
            $table->string('download_link')->nullable(); // For digital products
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category_id', 'status', 'sort_order']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
