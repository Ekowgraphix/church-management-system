<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('token', 255);
            $table->timestamp('created_at')->nullable();
            
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};
