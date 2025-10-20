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
        Schema::create('counselling_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('member_name', 150);
            $table->string('member_contact', 50)->nullable();
            $table->string('counsellor_name', 100);
            $table->string('topic', 100);
            $table->date('session_date');
            $table->time('session_time')->nullable();
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'follow_up', 'cancelled'])->default('pending');
            $table->boolean('is_confidential')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselling_sessions');
    }
};
