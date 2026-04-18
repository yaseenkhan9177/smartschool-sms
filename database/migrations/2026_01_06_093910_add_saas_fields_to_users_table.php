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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email'); // 'admin' (School Admin) default
            $table->string('school_name')->nullable()->after('role');
            $table->string('phone')->nullable()->after('school_name');
            $table->string('status')->default('active')->after('phone'); // 'active', 'suspended'
            $table->string('database_name')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'school_name', 'phone', 'status', 'database_name']);
        });
    }
};
