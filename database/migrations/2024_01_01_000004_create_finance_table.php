<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('donation_category_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->date('donation_date');
            $table->enum('payment_method', ['cash', 'check', 'card', 'bank_transfer', 'mobile_money', 'online'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['weekly', 'monthly', 'quarterly', 'yearly'])->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->boolean('receipt_sent')->default(false);
            $table->timestamp('receipt_sent_at')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['member_id', 'donation_date']);
            $table->index(['donation_category_id', 'donation_date']);
        });

        Schema::create('pledges', function (Blueprint $table) {
            $table->id();
            $table->string('pledge_number')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('campaign_name');
            $table->decimal('pledge_amount', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->date('pledge_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('frequency', ['one_time', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->enum('status', ['active', 'completed', 'cancelled', 'overdue'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pledge_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pledge_id')->constrained()->onDelete('cascade');
            $table->foreignId('donation_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('expense_categories')->onDelete('cascade');
            $table->decimal('budget_amount', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->foreignId('expense_category_id')->constrained()->onDelete('restrict');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->enum('payment_method', ['cash', 'check', 'card', 'bank_transfer', 'mobile_money'])->default('cash');
            $table->string('vendor_name')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->enum('period', ['monthly', 'quarterly', 'yearly'])->default('yearly');
            $table->decimal('total_amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_category_id')->constrained()->onDelete('cascade');
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('pledge_payments');
        Schema::dropIfExists('pledges');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('donation_categories');
    }
};
