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
            $table->enum('media_type', ['simple', 'gallery', 'before_after', 'video', 'mixed'])
                  ->default('simple')
                  ->after('thumbnail');
            $table->json('media_data')->nullable()->after('media_type');
            $table->decimal('average_rating', 3, 2)->default(0)->after('media_data');
            $table->unsignedInteger('reviews_count')->default(0)->after('average_rating');
            
            // Add indexes for performance
            $table->index(['media_type', 'status']);
            $table->index(['average_rating', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['media_type', 'status']);
            $table->dropIndex(['average_rating', 'status']);
            $table->dropColumn(['media_type', 'media_data', 'average_rating', 'reviews_count']);
        });
    }
};
