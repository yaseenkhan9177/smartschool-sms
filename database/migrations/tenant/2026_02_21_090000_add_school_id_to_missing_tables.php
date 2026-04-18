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
        $tables = [
            'expenses',
            'expense_categories',
            'fee_payments',
            'attendances',
            'school_classes',
            'subjects'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'school_id')) {
                        $table->unsignedBigInteger('school_id')->nullable()->after('id');
                        // If we want to strictly enforce it later:
                        // $table->foreign('school_id')->references('id')->on('users')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'expenses',
            'expense_categories',
            'fee_payments',
            'attendances',
            'school_classes',
            'subjects'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'school_id')) {
                        $table->dropColumn('school_id');
                    }
                });
            }
        }
    }
};
