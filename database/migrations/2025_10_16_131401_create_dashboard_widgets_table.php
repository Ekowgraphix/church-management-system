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
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_id')->constrained()->onDelete('cascade');
            $table->string('widget_type');
            $table->string('title');
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(1);
            $table->integer('height')->default(1);
            $table->json('config')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('refresh_interval')->default(0);
            $table->timestamps();
            
            $table->index('dashboard_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
    }
};
