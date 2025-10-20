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
        Schema::create('data_backups', function (Blueprint $table) {
            $table->id();
            $table->string('backup_name');
            $table->enum('backup_type', ['full', 'incremental', 'differential'])->default('full');
            $table->string('file_path');
            $table->bigInteger('file_size')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->boolean('is_encrypted')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_backups');
    }
};
