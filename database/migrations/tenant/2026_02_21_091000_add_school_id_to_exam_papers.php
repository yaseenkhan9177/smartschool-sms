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
        Schema::table('exam_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_papers', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_papers', function (Blueprint $table) {
            if (Schema::hasColumn('exam_papers', 'school_id')) {
                $table->dropColumn('school_id');
            }
        });
    }
};
