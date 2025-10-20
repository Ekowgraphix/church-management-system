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
        Schema::create('data_retention_policies', function (Blueprint $table) {
            $table->id();
            $table->string('data_type');
            $table->string('table_name');
            $table->integer('retention_days');
            $table->text('description')->nullable();
            $table->enum('action', ['delete', 'archive', 'anonymize'])->default('archive');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_executed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_retention_policies');
    }
};
