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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('payment_gateway'); // stripe, paypal, flutterwave, etc.
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer', 'mobile_money', 'paypal', 'other']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('card_last4')->nullable();
            $table->string('card_brand')->nullable();
            $table->text('gateway_response')->nullable();
            $table->string('receipt_url')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
