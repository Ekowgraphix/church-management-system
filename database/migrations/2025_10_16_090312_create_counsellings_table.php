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
        Schema::create('counsellings', function (Blueprint $table) {
            $table->id();
            $table->string('counsellor', 150);
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->foreignId('visitor_id')->nullable()->constrained('visitors')->onDelete('cascade');
            $table->dateTime('session_date');
            $table->text('issues');
            $table->text('outcome')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->enum('confidentiality', ['Normal', 'Private', 'Strict'])->default('Normal');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counsellings');
    }
};
