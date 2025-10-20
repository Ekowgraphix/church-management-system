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
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->text('description')->nullable();
            $table->decimal('goal_amount', 15, 2)->nullable();
            $table->decimal('raised_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->string('campaign_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('donor_count')->default(0);
            $table->timestamps();
        });

        // Add campaign_id to donations table
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('donation_category_id')->constrained('donation_campaigns')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_campaigns');
    }
};
