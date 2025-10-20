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
        Schema::create('welfare_requests', function (Blueprint $table) {
            $table->id();
            $table->string('member_name', 150);
            $table->string('member_contact', 50)->nullable();
            $table->string('request_type', 100);
            $table->text('description');
            $table->decimal('amount_requested', 10, 2)->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'declined', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfare_requests');
    }
};
