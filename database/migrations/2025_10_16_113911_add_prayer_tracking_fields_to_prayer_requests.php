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
        Schema::table('prayer_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('prayer_requests', 'privacy_level')) {
                $table->enum('privacy_level', ['public', 'members_only', 'prayer_team_only', 'private'])->default('public')->after('is_public');
            }
            if (!Schema::hasColumn('prayer_requests', 'answered_at')) {
                $table->timestamp('answered_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('prayer_requests', 'answer_testimony')) {
                $table->text('answer_testimony')->nullable()->after('answered_at');
            }
            if (!Schema::hasColumn('prayer_requests', 'prayer_count')) {
                $table->integer('prayer_count')->default(0)->after('answer_testimony');
            }
            if (!Schema::hasColumn('prayer_requests', 'last_prayed_at')) {
                $table->timestamp('last_prayed_at')->nullable()->after('prayer_count');
            }
            if (!Schema::hasColumn('prayer_requests', 'notify_prayer_chain')) {
                $table->boolean('notify_prayer_chain')->default(false)->after('is_urgent');
            }
            if (!Schema::hasColumn('prayer_requests', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('answered_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            //
        });
    }
};
