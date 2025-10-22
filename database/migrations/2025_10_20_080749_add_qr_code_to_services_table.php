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
        Schema::table('services', function (Blueprint $table) {
            $table->string('qr_code_token')->unique()->nullable()->after('is_active');
            $table->timestamp('qr_code_generated_at')->nullable()->after('qr_code_token');
            $table->integer('qr_check_in_radius')->default(100)->after('qr_code_generated_at')->comment('Check-in radius in meters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['qr_code_token', 'qr_code_generated_at', 'qr_check_in_radius']);
        });
    }
};
