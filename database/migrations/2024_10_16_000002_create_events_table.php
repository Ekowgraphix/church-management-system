<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_type'); // service, meeting, conference, social, outreach
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('requires_registration')->default(false);
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed, cancelled
            $table->string('image')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('registered'); // registered, attended, cancelled
            $table->dateTime('registered_at');
            $table->dateTime('attended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'member_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
    }
};
