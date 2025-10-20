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
        Schema::create('member_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('transfer_type', ['transfer_in', 'transfer_out']); 
            $table->string('previous_church')->nullable();
            $table->string('new_church')->nullable();
            $table->string('pastor_name')->nullable();
            $table->string('pastor_email')->nullable();
            $table->string('pastor_phone')->nullable();
            $table->date('request_date');
            $table->date('approval_date')->nullable();
            $table->date('transfer_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('letter_of_transfer')->nullable(); // File path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_transfers');
    }
};
