<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('variables')->nullable();
            $table->enum('category', ['general', 'birthday', 'anniversary', 'event', 'reminder', 'followup', 'prayer'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('message');
            $table->foreignId('template_id')->nullable()->constrained('sms_templates')->onDelete('set null');
            $table->enum('recipient_type', ['all_members', 'specific_members', 'group', 'custom'])->default('all_members');
            $table->json('recipient_filters')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('status', ['draft', 'scheduled', 'sending', 'completed', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('sms_campaigns')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('cost', 10, 4)->nullable();
            $table->timestamps();
            
            $table->index(['campaign_id', 'status']);
            $table->index(['member_id', 'created_at']);
        });

        Schema::create('sms_opt_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->date('opted_out_at');
            $table->text('reason')->nullable();
            $table->timestamps();
            
            $table->unique(['member_id', 'phone_number']);
        });

        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('requester_name')->nullable();
            $table->string('requester_phone')->nullable();
            $table->string('requester_email')->nullable();
            $table->text('request')->nullable();
            $table->enum('category', ['health', 'family', 'financial', 'spiritual', 'other'])->default('other');
            $table->enum('privacy_level', ['public', 'leaders_only', 'private'])->default('public');
            $table->enum('status', ['pending', 'praying', 'answered'])->default('pending');
            $table->boolean('is_urgent')->default(false);
            $table->date('request_date')->nullable();
            $table->date('answered_date')->nullable();
            $table->text('answer_testimony')->nullable();
            $table->integer('prayer_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('prayer_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prayer_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('response')->nullable();
            $table->boolean('is_praying')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_responses');
        Schema::dropIfExists('prayer_requests');
        Schema::dropIfExists('sms_opt_outs');
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_campaigns');
        Schema::dropIfExists('sms_templates');
    }
};
