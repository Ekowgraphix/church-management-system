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
        Schema::create('security_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_type');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('resource_accessed')->nullable();
            $table->text('additional_data')->nullable();
            $table->enum('status', ['open', 'investigating', 'resolved', 'false_positive'])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['incident_type', 'created_at']);
            $table->index(['severity', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_incidents');
    }
};
