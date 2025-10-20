<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devotionals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('devotional_date');
            $table->text('scripture_reference');
            $table->text('scripture_text');
            $table->text('message');
            $table->text('prayer')->nullable();
            $table->text('reflection_questions')->nullable();
            $table->string('author')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->index('devotional_date');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devotionals');
    }
};
