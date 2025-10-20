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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('prayer_requests', 'title')) {
                $table->string('title')->nullable()->after('requester_name');
            }
            if (!Schema::hasColumn('prayer_requests', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('prayer_requests', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('status');
            }
            if (!Schema::hasColumn('prayer_requests', 'requested_by')) {
                $table->foreignId('requested_by')->nullable()->after('is_urgent')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('prayer_requests', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->after('requested_by')->constrained('users')->onDelete('set null');
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
