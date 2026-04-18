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
        Schema::table('school_requests', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
