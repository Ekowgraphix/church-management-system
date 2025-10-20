<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance visitors table
        if (Schema::hasTable('visitors')) {
            Schema::table('visitors', function (Blueprint $table) {
                if (!Schema::hasColumn('visitors', 'qr_code')) {
                    $table->string('qr_code', 100)->nullable()->after('phone');
                }
                if (!Schema::hasColumn('visitors', 'service_attended')) {
                    $table->string('service_attended', 150)->nullable();
                }
                if (!Schema::hasColumn('visitors', 'invited_by')) {
                    $table->string('invited_by', 100)->nullable();
                }
                if (!Schema::hasColumn('visitors', 'feedback')) {
                    $table->text('feedback')->nullable();
                }
                if (!Schema::hasColumn('visitors', 'assigned_to')) {
                    $table->string('assigned_to', 100)->nullable();
                }
                if (!Schema::hasColumn('visitors', 'conversion_status')) {
                    $table->enum('conversion_status', ['visitor', 'returning', 'converted'])->default('visitor');
                }
                if (!Schema::hasColumn('visitors', 'visit_count')) {
                    $table->integer('visit_count')->default(1);
                }
                if (!Schema::hasColumn('visitors', 'last_contact_date')) {
                    $table->date('last_contact_date')->nullable();
                }
                if (!Schema::hasColumn('visitors', 'next_followup_date')) {
                    $table->date('next_followup_date')->nullable();
                }
                if (!Schema::hasColumn('visitors', 'sms_sent')) {
                    $table->boolean('sms_sent')->default(false);
                }
                if (!Schema::hasColumn('visitors', 'email_sent')) {
                    $table->boolean('email_sent')->default(false);
                }
            });
        }

        // Visitor attendance history
        if (!Schema::hasTable('visitor_attendance')) {
            Schema::create('visitor_attendance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
                $table->string('service_name', 100);
                $table->date('attendance_date');
                $table->time('check_in_time')->nullable();
                $table->string('check_in_method', 50)->default('manual');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Visitor follow-up logs
        if (!Schema::hasTable('visitor_followup_logs')) {
            Schema::create('visitor_followup_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
                $table->foreignId('followup_by')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('contact_method', ['phone', 'sms', 'email', 'visit', 'whatsapp'])->default('phone');
                $table->text('notes')->nullable();
                $table->string('voice_note_path')->nullable();
                $table->enum('outcome', ['contacted', 'no_answer', 'interested', 'not_interested', 'converted'])->default('contacted');
                $table->date('next_followup_date')->nullable();
                $table->timestamps();
            });
        }

        // Visitor journey milestones
        if (!Schema::hasTable('visitor_journey')) {
            Schema::create('visitor_journey', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
                $table->string('milestone', 100);
                $table->text('description')->nullable();
                $table->timestamp('achieved_at');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_journey');
        Schema::dropIfExists('visitor_followup_logs');
        Schema::dropIfExists('visitor_attendance');
    }
};
