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
        Schema::create('member_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('stage', ['inquiry', 'visitor', 'new_member_class', 'baptism', 'membership_complete'])->default('inquiry');
            $table->date('inquiry_date')->nullable();
            $table->date('first_visit_date')->nullable();
            $table->date('class_enrollment_date')->nullable();
            $table->date('class_completion_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->date('membership_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_mentor')->nullable()->constrained('members')->onDelete('set null');
            $table->boolean('welcome_packet_sent')->default(false);
            $table->boolean('orientation_completed')->default(false);
            $table->boolean('membership_covenant_signed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_onboardings');
    }
};
