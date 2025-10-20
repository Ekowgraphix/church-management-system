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
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('password_min_length')->default(8);
            $table->boolean('password_require_uppercase')->default(true);
            $table->boolean('password_require_lowercase')->default(true);
            $table->boolean('password_require_numbers')->default(true);
            $table->boolean('password_require_symbols')->default(false);
            $table->integer('password_expiry_days')->default(90);
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration_minutes')->default(30);
            $table->integer('session_timeout_minutes')->default(120);
            $table->boolean('force_2fa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
