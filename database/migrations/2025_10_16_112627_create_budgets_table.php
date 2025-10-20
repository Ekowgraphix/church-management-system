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
        // Drop existing budgets table if it exists
        Schema::dropIfExists('budgets');
        
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name');
            $table->foreignId('expense_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->integer('fiscal_year');
            $table->enum('period', ['monthly', 'quarterly', 'yearly'])->default('yearly');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'exceeded', 'completed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add department_id to expenses table if not exists
        if (!Schema::hasColumn('expenses', 'department_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->after('expense_category_id')->constrained()->onDelete('set null');
            });
        }

        // Add budget_limit to expense_categories if not exists
        if (!Schema::hasColumn('expense_categories', 'budget_limit')) {
            Schema::table('expense_categories', function (Blueprint $table) {
                $table->decimal('budget_limit', 15, 2)->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
