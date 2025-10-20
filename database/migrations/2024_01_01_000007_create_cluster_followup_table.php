<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('location')->nullable();
            $table->string('meeting_day')->nullable();
            $table->time('meeting_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cluster_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('joined_date');
            $table->date('left_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->unique(['cluster_id', 'member_id']);
        });

        Schema::create('followup_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('cluster_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('followup_type_id')->constrained()->onDelete('restrict');
            $table->foreignId('assigned_to')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('due_date');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('completed_date')->nullable();
            $table->text('outcome')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['assigned_to', 'status', 'due_date']);
            $table->index(['member_id', 'status']);
        });

        Schema::create('followup_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('followup_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->date('activity_date');
            $table->enum('activity_type', ['call', 'visit', 'email', 'sms', 'meeting', 'other'])->default('call');
            $table->text('notes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('followup_activities');
        Schema::dropIfExists('followups');
        Schema::dropIfExists('followup_types');
        Schema::dropIfExists('cluster_members');
        Schema::dropIfExists('clusters');
    }
};
