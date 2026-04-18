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
        Schema::table('students', function (Blueprint $table) {
            // Drop department if it exists
            if (Schema::hasColumn('students', 'department')) {
                $table->dropColumn('department');
            }

            // Add new columns
            if (!Schema::hasColumn('students', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('students', 'parent_phone')) {
                $table->string('parent_phone')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('students', 'status')) {
                $table->string('status')->default('pending')->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'class_id')) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            }
            if (Schema::hasColumn('students', 'parent_phone')) {
                $table->dropColumn('parent_phone');
            }
            if (Schema::hasColumn('students', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('students', 'department')) {
                $table->string('department')->nullable();
            }
        });
    }
};
