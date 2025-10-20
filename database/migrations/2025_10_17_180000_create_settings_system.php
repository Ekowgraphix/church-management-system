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
        // Main settings table (key-value store)
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->index(); // general, communication, finance, etc.
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text'); // text, number, boolean, json, file
            $table->text('description')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();
            });
        }

        // User roles and permissions
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Array of permission keys
            $table->string('dashboard_route')->default('/dashboard');
            $table->boolean('is_active')->default(true);
            $table->integer('users_count')->default(0);
            $table->timestamps();
            });
        }

        // Role permissions (granular)
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('user_roles')->onDelete('cascade');
            $table->string('module', 50); // members, finance, events, etc.
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->boolean('can_export')->default(false);
            $table->timestamps();
            
            $table->unique(['role_id', 'module']);
            });
        }

        // User login history
        if (!Schema::hasTable('user_login_history')) {
            Schema::create('user_login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('device_type', 50)->nullable(); // desktop, mobile, tablet
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('location')->nullable(); // City, Country
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->timestamp('logged_in_at');
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();
            });
        }

        // Activity logs (audit trail)
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action', 50); // created, updated, deleted, viewed
            $table->string('module', 50); // members, finance, events
            $table->string('model_type')->nullable(); // App\Models\Member
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
            });
        }

        // Message templates
        if (!Schema::hasTable('message_templates')) {
            Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->enum('type', ['email', 'sms', 'both'])->default('both');
            $table->string('category', 50); // visitor_followup, birthday, tithe_thanks
            $table->string('subject')->nullable(); // For emails
            $table->text('content');
            $table->json('variables')->nullable(); // Available placeholders
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            });
        }

        // Communication logs
        if (!Schema::hasTable('communication_logs')) {
            Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['email', 'sms'])->default('email');
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->string('provider')->nullable(); // sendgrid, twilio, etc.
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'status', 'created_at']);
            });
        }

        // System backups
        if (!Schema::hasTable('system_backups')) {
            Schema::create('system_backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->default(0); // in bytes
            $table->enum('type', ['manual', 'automatic'])->default('manual');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            });
        }

        // Integration configs
        if (!Schema::hasTable('integrations')) {
            Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // paystack, sendgrid, twilio
            $table->string('provider', 50);
            $table->enum('category', ['payment', 'communication', 'storage', 'calendar'])->default('payment');
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable(); // API keys, settings
            $table->timestamp('last_sync_at')->nullable();
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('communication_logs');
        Schema::dropIfExists('message_templates');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('user_login_history');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('settings');
    }
};
