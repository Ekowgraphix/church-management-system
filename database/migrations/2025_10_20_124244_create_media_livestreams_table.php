<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_livestreams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->foreignId('started_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('platform', ['youtube', 'facebook', 'custom_rtmp'])->default('youtube');
            $table->string('stream_key')->nullable();
            $table->string('stream_url')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->string('facebook_video_id')->nullable();
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled'])->default('scheduled');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('peak_viewers')->default(0);
            $table->unsignedInteger('total_views')->default(0);
            $table->text('stream_notes')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_livestreams');
    }
};