<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('action_url')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read', 'created_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event');
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('description');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('message');
            $table->text('context')->nullable();
            $table->text('extra')->nullable();
            $table->timestamps();
            
            $table->index(['level', 'created_at']);
        });

        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->string('field_name');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'number', 'date', 'select', 'checkbox', 'textarea'])->default('text');
            $table->json('field_options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('settings');
    }
};
