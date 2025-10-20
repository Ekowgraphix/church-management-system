<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Partners (organizations/individuals)
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->enum('type', ['individual', 'organization', 'church', 'ministry', 'business'])->default('individual');
                $table->string('contact_person')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('website')->nullable();
                $table->text('description')->nullable();
                $table->date('partnership_start_date');
                $table->enum('status', ['active', 'inactive', 'pending', 'ended'])->default('active');
                $table->string('logo_path')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Partnership agreements
        if (!Schema::hasTable('partnership_agreements')) {
            Schema::create('partnership_agreements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->decimal('commitment_amount', 12, 2)->nullable();
                $table->enum('frequency', ['one-time', 'monthly', 'quarterly', 'yearly'])->nullable();
                $table->string('document_path')->nullable();
                $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
                $table->timestamps();
            });
        }

        // Partnership activities/contributions
        if (!Schema::hasTable('partnership_activities')) {
            Schema::create('partnership_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('agreement_id')->nullable()->constrained('partnership_agreements');
                $table->enum('type', ['donation', 'service', 'event', 'project', 'support', 'volunteer'])->default('donation');
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('value', 12, 2)->nullable();
                $table->date('activity_date');
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Communication log
        if (!Schema::hasTable('partner_communications')) {
            Schema::create('partner_communications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained(); // Who communicated
                $table->enum('type', ['email', 'phone', 'meeting', 'letter', 'video_call'])->default('email');
                $table->string('subject');
                $table->text('notes');
                $table->date('communication_date');
                $table->enum('outcome', ['successful', 'follow_up_needed', 'no_response'])->nullable();
                $table->timestamps();
            });
        }

        // Impact reports
        if (!Schema::hasTable('partnership_reports')) {
            Schema::create('partnership_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('summary');
                $table->date('report_date');
                $table->string('file_path')->nullable();
                $table->json('metrics')->nullable(); // Key metrics/stats
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_reports');
        Schema::dropIfExists('partner_communications');
        Schema::dropIfExists('partnership_activities');
        Schema::dropIfExists('partnership_agreements');
        Schema::dropIfExists('partners');
    }
};
