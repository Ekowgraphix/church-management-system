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
        Schema::create('children_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children_ministries')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->string('reason'); // e.g., 'Perfect Attendance', 'Memory Verse', 'Helping Others'
            $table->enum('type', ['earned', 'redeemed'])->default('earned');
            $table->date('awarded_date');
            $table->string('awarded_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_points');
    }
};
