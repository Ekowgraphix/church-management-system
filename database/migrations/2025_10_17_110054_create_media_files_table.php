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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('file_path');
            $table->string('file_type', 50); // image, video
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('tags')->nullable();
            $table->string('uploaded_by', 100);
            $table->string('event_name', 150)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
