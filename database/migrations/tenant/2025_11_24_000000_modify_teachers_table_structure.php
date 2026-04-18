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
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'education_level')) {
                $table->string('education_level')->nullable()->after('subject');
            }
            if (Schema::hasColumn('teachers', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'education_level')) {
                $table->dropColumn('education_level');
            }
            if (!Schema::hasColumn('teachers', 'semester')) {
                $table->string('semester')->nullable();
            }
        });
    }
};
