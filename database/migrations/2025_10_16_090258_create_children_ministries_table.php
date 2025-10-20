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
        Schema::create('children_ministries', function (Blueprint $table) {
            $table->id();
            $table->string('child_name');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('parent_name');
            $table->string('contact', 50);
            $table->text('address')->nullable();
            $table->string('class_group', 100)->nullable(); // Nursery, Teens, Pre-teens
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->date('registered_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_ministries');
    }
};
