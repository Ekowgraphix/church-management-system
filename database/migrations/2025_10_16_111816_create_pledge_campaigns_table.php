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
        Schema::create('pledge_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->text('description')->nullable();
            $table->decimal('goal_amount', 15, 2)->nullable();
            $table->decimal('pledged_amount', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->integer('total_pledges')->default(0);
            $table->integer('fulfilled_pledges')->default(0);
            $table->boolean('send_reminders')->default(true);
            $table->integer('reminder_frequency_days')->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add campaign_id to pledges table
        Schema::table('pledges', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('member_id')->constrained('pledge_campaigns')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledge_campaigns');
    }
};
