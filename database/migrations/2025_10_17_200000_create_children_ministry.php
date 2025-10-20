<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Children profiles
        if (!Schema::hasTable('children')) {
            Schema::create('children', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->date('date_of_birth');
                $table->string('gender', 10);
                $table->text('medical_info')->nullable();
                $table->text('allergies')->nullable();
                $table->text('special_needs')->nullable();
                $table->text('emergency_notes')->nullable();
                $table->foreignId('family_id')->nullable()->constrained('members')->onDelete('set null');
                $table->string('photo_path')->nullable();
                $table->string('qr_code', 100)->nullable();
                $table->boolean('photo_consent')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Parents/Guardians
        if (!Schema::hasTable('child_guardians')) {
            Schema::create('child_guardians', function (Blueprint $table) {
                $table->id();
                $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
                $table->string('name');
                $table->string('relationship', 50);
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->boolean('can_pickup')->default(true);
                $table->boolean('is_primary')->default(false);
                $table->boolean('emergency_contact')->default(false);
                $table->timestamps();
            });
        }

        // Check-in/Check-out
        if (!Schema::hasTable('children_checkins')) {
            Schema::create('children_checkins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
                $table->foreignId('checked_in_by')->constrained('users');
                $table->foreignId('checked_out_by')->nullable()->constrained('users');
                $table->foreignId('picked_up_by_guardian')->nullable()->constrained('child_guardians');
                $table->timestamp('check_in_time');
                $table->timestamp('check_out_time')->nullable();
                $table->string('class_assigned', 100)->nullable();
                $table->string('security_code', 10)->nullable(); // Pickup code
                $table->text('notes')->nullable();
                $table->text('incident_report')->nullable();
                $table->timestamps();
            });
        }

        // Classes/Groups
        if (!Schema::hasTable('children_classes')) {
            Schema::create('children_classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('age_group', 50);
                $table->integer('min_age');
                $table->integer('max_age');
                $table->integer('capacity')->default(20);
                $table->integer('current_enrollment')->default(0);
                $table->foreignId('teacher_id')->nullable()->constrained('users');
                $table->string('room_location')->nullable();
                $table->string('meeting_time')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Attendance tracking
        if (!Schema::hasTable('children_attendance')) {
            Schema::create('children_attendance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
                $table->foreignId('class_id')->nullable()->constrained('children_classes');
                $table->date('attendance_date');
                $table->enum('status', ['present', 'absent', 'late'])->default('present');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('children_attendance');
        Schema::dropIfExists('children_classes');
        Schema::dropIfExists('children_checkins');
        Schema::dropIfExists('child_guardians');
        Schema::dropIfExists('children');
    }
};
