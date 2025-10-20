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
        Schema::table('media_teams', function (Blueprint $table) {
            // Add missing columns (SQLite doesn't support 'after', so they'll be added at the end)
            $table->string('name', 100)->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('assigned_event', 150)->nullable();
            $table->string('status', 20)->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_teams', function (Blueprint $table) {
            $table->dropColumn(['name', 'contact', 'email', 'assigned_event', 'status']);
        });
    }
};
