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
        Schema::create('welfare_funds', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['income', 'expense']);
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->string('approved_by', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfare_funds');
    }
};
