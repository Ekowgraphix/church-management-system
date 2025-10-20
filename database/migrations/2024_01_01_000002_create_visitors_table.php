<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('visit_date');
            $table->string('service_attended')->nullable();
            $table->enum('visit_type', ['first_time', 'returning', 'guest'])->default('first_time');
            $table->string('invited_by')->nullable();
            $table->text('interests')->nullable();
            $table->text('prayer_requests')->nullable();
            $table->boolean('wants_followup')->default(true);
            $table->enum('followup_status', ['pending', 'contacted', 'completed', 'no_response'])->default('pending');
            $table->date('followup_date')->nullable();
            $table->text('followup_notes')->nullable();
            $table->boolean('converted_to_member')->default(false);
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visitor_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('followed_up_by')->constrained('users');
            $table->date('followup_date');
            $table->enum('method', ['phone', 'email', 'visit', 'sms', 'other']);
            $table->text('notes')->nullable();
            $table->enum('outcome', ['positive', 'neutral', 'negative', 'no_response'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_followups');
        Schema::dropIfExists('visitors');
    }
};
