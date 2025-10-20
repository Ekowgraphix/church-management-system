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
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('organization');
            $table->string('contact_person', 150);
            $table->string('phone', 50);
            $table->string('email', 150)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('contribution', 200)->nullable(); // Financial, Equipment, Services
            $table->text('terms')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};
