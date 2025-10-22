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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['video', 'image', 'audio', 'document'])->default('image');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url')->nullable();
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('storage_provider')->default('local'); // local, s3, cloudinary
            $table->json('metadata')->nullable(); // width, height, duration, etc.
            $table->json('tags')->nullable(); // AI auto-tags or manual tags
            $table->string('category')->nullable(); // sermon, worship, youth, event, etc.
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'category']);
            $table->index('uploaded_by');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
