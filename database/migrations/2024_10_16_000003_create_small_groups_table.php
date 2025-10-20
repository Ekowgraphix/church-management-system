<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('small_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // bible_study, prayer, youth, men, women, couples, children, other
            $table->foreignId('leader_id')->nullable()->constrained('members');
            $table->string('meeting_day')->nullable(); // monday, tuesday, etc
            $table->time('meeting_time')->nullable();
            $table->string('location')->nullable();
            $table->integer('max_members')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('small_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('small_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('joined_date');
            $table->string('role')->default('member'); // leader, co-leader, member
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['small_group_id', 'member_id']);
        });

        Schema::create('small_group_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('small_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('meeting_date');
            $table->time('check_in_time')->nullable();
            $table->string('status')->default('present'); // present, absent, excused
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('small_group_attendance');
        Schema::dropIfExists('small_group_members');
        Schema::dropIfExists('small_groups');
    }
};
