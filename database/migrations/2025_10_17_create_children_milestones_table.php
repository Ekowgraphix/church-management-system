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
        Schema::create('children_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children_ministries')->onDelete('cascade');
            $table->string('milestone_type'); // e.g., 'First Bible Verse', 'Baptism', 'First Prayer'
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('achieved_date');
            $table->string('verified_by')->nullable();
            $table->string('badge_icon')->nullable(); // Icon name for display
            $table->string('badge_color')->nullable(); // Color for badge
            $table->integer('points_awarded')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_milestones');
    }
};
