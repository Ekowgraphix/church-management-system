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
        Schema::create('membership_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('instructor')->nullable();
            $table->string('location')->nullable();
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('requirements')->nullable();
            $table->timestamps();
        });

        // Pivot table for class enrollments
        Schema::create('class_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('enrolled_date');
            $table->date('completed_date')->nullable();
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped'])->default('enrolled');
            $table->integer('attendance_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_classes');
    }
};
