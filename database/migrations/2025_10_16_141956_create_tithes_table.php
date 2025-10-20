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
        Schema::create('tithes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->default('Cash'); // Cash, Mobile Money, Bank Transfer
            $table->string('received_by', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tithes');
    }
};
