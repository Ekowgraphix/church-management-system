<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_event_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['photographer', 'videographer', 'editor', 'livestream_operator', 'designer'])->default('photographer');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['event_id', 'assigned_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_event_assignments');
    }
};