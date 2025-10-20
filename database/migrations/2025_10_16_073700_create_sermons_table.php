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
        Schema::create('sermon_series', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->nullable()->constrained('sermon_series')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('speaker');
            $table->date('sermon_date');
            $table->text('scripture_reference')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('video_file')->nullable();
            $table->string('notes_file')->nullable();
            $table->text('summary')->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->boolean('is_published')->default(true);
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermons');
        Schema::dropIfExists('sermon_series');
    }
};
