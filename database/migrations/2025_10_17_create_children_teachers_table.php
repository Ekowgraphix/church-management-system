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
        Schema::create('children_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('class_group'); // e.g., 'Nursery', 'Toddlers', 'Primary'
            $table->enum('role', ['Lead Teacher', 'Assistant', 'Substitute'])->default('Lead Teacher');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->date('start_date')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_teachers');
    }
};
