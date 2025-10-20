<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('children') && !Schema::hasColumn('children', 'deleted_at')) {
            Schema::table('children', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('children', 'deleted_at')) {
            Schema::table('children', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
