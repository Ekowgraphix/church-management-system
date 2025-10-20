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
        Schema::create('welfares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->string('case_type', 100); // Bereavement, Medical Aid, Support
            $table->text('description');
            $table->decimal('amount_requested', 12, 2)->nullable();
            $table->decimal('amount_disbursed', 12, 2)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Declined'])->default('Pending');
            $table->string('handled_by', 150)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfares');
    }
};
