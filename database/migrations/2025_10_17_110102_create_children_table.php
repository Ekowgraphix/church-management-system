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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->date('dob');
            $table->string('guardian_name', 150);
            $table->string('guardian_contact', 50)->nullable();
            $table->string('guardian_email', 100)->nullable();
            $table->string('class_group', 100)->nullable();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->text('medical_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
