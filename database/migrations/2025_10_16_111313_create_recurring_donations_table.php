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
        Schema::create('recurring_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('donation_category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('campaign_id')->nullable()->constrained('donation_campaigns')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['weekly', 'monthly', 'quarterly', 'yearly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_payment_date');
            $table->enum('status', ['active', 'paused', 'cancelled', 'completed'])->default('active');
            $table->string('payment_method');
            $table->string('payment_token')->nullable(); // For gateway subscription
            $table->integer('total_payments')->default(0);
            $table->decimal('total_amount_paid', 15, 2)->default(0);
            $table->timestamp('last_payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_donations');
    }
};
