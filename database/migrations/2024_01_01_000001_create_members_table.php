<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->date('wedding_anniversary')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->enum('membership_status', ['active', 'inactive', 'transferred', 'deceased'])->default('active');
            $table->date('membership_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->string('baptism_certificate')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('member_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('relationship');
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('member_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('member_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('previous_status');
            $table->string('new_status');
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_status_history');
        Schema::dropIfExists('member_documents');
        Schema::dropIfExists('member_emergency_contacts');
        Schema::dropIfExists('members');
    }
};
