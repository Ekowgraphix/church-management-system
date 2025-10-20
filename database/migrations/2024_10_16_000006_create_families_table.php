<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_name');
            $table->foreignId('head_of_family_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('relationship'); // head, spouse, child, parent, sibling, other
            $table->timestamps();
            
            $table->unique(['family_id', 'member_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('family_members');
        Schema::dropIfExists('families');
    }
};
