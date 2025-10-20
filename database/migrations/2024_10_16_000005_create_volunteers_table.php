<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('volunteer_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('department'); // worship, children, hospitality, media, security, other
            $table->integer('required_volunteers')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_role_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->date('assignment_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, confirmed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('volunteer_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('day_of_week'); // monday, tuesday, etc
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('volunteer_availability');
        Schema::dropIfExists('volunteer_assignments');
        Schema::dropIfExists('volunteer_roles');
    }
};
