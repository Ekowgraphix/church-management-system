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
        Schema::create('offerings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('service_name', 100)->nullable();
            $table->string('collected_by', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('category', 100)->nullable(); // Sunday Offering, Thanksgiving, etc.
            $table->string('payment_method', 50)->default('Cash'); // Cash, Mobile Money, Cheque
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('date');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerings');
    }
};
