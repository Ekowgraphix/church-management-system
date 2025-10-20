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
        // Create counsellors table
        Schema::create('counsellors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('specialization', ['Marriage', 'Family', 'Personal', 'Youth', 'Career', 'Grief', 'Spiritual', 'General'])->default('General');
            $table->enum('status', ['Active', 'Inactive', 'On Leave'])->default('Active');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->integer('total_sessions')->default(0);
            $table->integer('rating')->default(5);
            $table->timestamps();
        });

        // Create session categories table
        Schema::create('counselling_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('icon', 50)->default('comments');
            $table->string('color', 20)->default('blue');
            $table->text('description')->nullable();
            $table->boolean('requires_specialist')->default(false);
            $table->integer('session_count')->default(0);
            $table->timestamps();
        });

        // Create follow-up reminders table
        Schema::create('counselling_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('counselling_sessions')->onDelete('cascade');
            $table->foreignId('counsellor_id')->nullable()->constrained('counsellors')->onDelete('set null');
            $table->date('follow_up_date');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'Completed', 'Missed', 'Rescheduled'])->default('Pending');
            $table->text('notes')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Create session notes (for AI summarization)
        Schema::create('counselling_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('counselling_sessions')->onDelete('cascade');
            $table->text('original_notes');
            $table->text('ai_summary')->nullable();
            $table->json('key_points')->nullable();
            $table->json('action_items')->nullable();
            $table->boolean('is_encrypted')->default(true);
            $table->timestamp('summarized_at')->nullable();
            $table->timestamps();
        });

        // Add new columns to counselling_sessions if not exist
        if (!Schema::hasColumn('counselling_sessions', 'counsellor_id')) {
            Schema::table('counselling_sessions', function (Blueprint $table) {
                $table->foreignId('counsellor_id')->nullable()->after('id')->constrained('counsellors')->onDelete('set null');
                $table->foreignId('category_id')->nullable()->after('counsellor_id')->constrained('counselling_categories')->onDelete('set null');
                $table->enum('session_type', ['In-Person', 'Phone', 'Video Call', 'Group'])->default('In-Person')->after('topic');
                $table->integer('duration')->default(60)->after('session_time'); // in minutes
                $table->text('outcome')->nullable()->after('notes');
                $table->integer('rating')->nullable()->after('outcome'); // 1-5 rating
                $table->boolean('requires_followup')->default(false)->after('follow_up_date');
                $table->string('access_level', 20)->default('confidential')->after('is_confidential');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselling_notes');
        Schema::dropIfExists('counselling_followups');
        Schema::dropIfExists('counselling_categories');
        Schema::dropIfExists('counsellors');
        
        // Remove added columns
        if (Schema::hasTable('counselling_sessions')) {
            Schema::table('counselling_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('counselling_sessions', 'counsellor_id')) {
                    $table->dropForeign(['counsellor_id']);
                    $table->dropColumn(['counsellor_id', 'category_id', 'session_type', 'duration', 'outcome', 'rating', 'requires_followup', 'access_level']);
                }
            });
        }
    }
};
