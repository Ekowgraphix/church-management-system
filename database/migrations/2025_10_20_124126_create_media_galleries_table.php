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
        Schema::create('media_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_downloads')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Pivot table for gallery items
        Schema::create('gallery_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('media_galleries')->onDelete('cascade');
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->string('caption')->nullable();
            $table->string('photographer')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['gallery_id', 'media_file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_media');
        Schema::dropIfExists('media_galleries');
    }
};
