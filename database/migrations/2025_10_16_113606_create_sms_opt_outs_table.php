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
        Schema::dropIfExists('sms_opt_outs');
        
        Schema::create('sms_opt_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->text('reason')->nullable();
            $table->timestamp('opted_out_at');
            $table->timestamps();
            
            $table->unique('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_opt_outs');
    }
};
