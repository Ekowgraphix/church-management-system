<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('visitor_id')->nullable()->constrained()->onDelete('cascade');
            $table->time('check_in_time');
            $table->time('check_out_time')->nullable();
            $table->enum('check_in_method', ['qr_code', 'manual', 'mobile', 'kiosk'])->default('manual');
            $table->string('check_in_location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['service_id', 'attendance_date']);
            $table->index(['member_id', 'attendance_date']);
        });

        Schema::create('attendance_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('qr_code')->unique();
            $table->string('qr_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('absence_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('absence_date');
            $table->integer('consecutive_absences')->default(1);
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();
            $table->enum('notification_method', ['email', 'sms', 'both'])->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absence_notifications');
        Schema::dropIfExists('attendance_qr_codes');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('services');
    }
};
