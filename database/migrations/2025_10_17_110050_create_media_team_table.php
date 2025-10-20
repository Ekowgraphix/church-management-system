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
        Schema::create('media_team', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('role', 100);
            $table->string('contact', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('assigned_event', 150)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_team');
    }
};
