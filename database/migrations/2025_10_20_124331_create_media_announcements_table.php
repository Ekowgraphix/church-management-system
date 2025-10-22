<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->json('platforms')->nullable(); // ['facebook', 'instagram', 'whatsapp', 'website']
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('analytics')->nullable(); // impressions, clicks, shares
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_announcements');
    }
};