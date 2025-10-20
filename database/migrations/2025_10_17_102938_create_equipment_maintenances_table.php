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
        Schema::create('equipment_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->date('maintenance_date');
            $table->enum('maintenance_type', ['routine', 'repair', 'inspection', 'upgrade'])->default('routine');
            $table->text('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('performed_by')->nullable();
            $table->string('vendor')->nullable();
            $table->text('notes')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenances');
    }
};
