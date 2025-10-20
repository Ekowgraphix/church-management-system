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
        Schema::create('prayer_chain_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('notification_preference', ['email', 'sms', 'both'])->default('email');
            $table->boolean('is_active')->default(true);
            $table->integer('prayers_received')->default(0);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });

        // Prayer chain participants for specific requests
        Schema::create('prayer_chain_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prayer_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('prayer_chain_member_id')->constrained()->onDelete('cascade');
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->boolean('prayed')->default(false);
            $table->timestamp('prayed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_chain_members');
    }
};
