<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('baptism_status')->nullable()->after('baptism_date');
            $table->text('ministry_interests')->nullable()->after('baptism_status');
            $table->text('prayer_requests')->nullable()->after('ministry_interests');
            $table->text('spiritual_gifts')->nullable()->after('prayer_requests');
            $table->text('salvation_testimony')->nullable()->after('spiritual_gifts');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'baptism_status',
                'ministry_interests',
                'prayer_requests',
                'spiritual_gifts',
                'salvation_testimony'
            ]);
        });
    }
};
