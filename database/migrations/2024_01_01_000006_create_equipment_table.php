<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('equipment_categories')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->string('location')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'damaged'])->default('good');
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->date('warranty_expiry')->nullable();
            $table->integer('maintenance_interval_days')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('equipment_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users');
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue'])->default('active');
            $table->text('return_notes')->nullable();
            $table->foreignId('assigned_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('equipment_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->date('maintenance_date');
            $table->enum('maintenance_type', ['routine', 'repair', 'inspection', 'upgrade'])->default('routine');
            $table->text('description');
            $table->decimal('cost', 15, 2)->nullable();
            $table->string('performed_by')->nullable();
            $table->string('vendor')->nullable();
            $table->text('notes')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenance');
        Schema::dropIfExists('equipment_assignments');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('equipment_categories');
    }
};
