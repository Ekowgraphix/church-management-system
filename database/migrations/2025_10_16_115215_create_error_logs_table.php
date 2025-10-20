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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('severity')->default('error');
            $table->string('error_type');
            $table->text('error_message');
            $table->text('stack_trace')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('context')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['severity', 'created_at']);
            $table->index('resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
