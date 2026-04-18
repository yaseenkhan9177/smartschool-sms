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
        Schema::table('student_fees', function (Blueprint $table) {
            $table->decimal('late_fee', 10, 2)->default(0)->after('status');
            $table->decimal('discount', 10, 2)->default(0)->after('late_fee');
            $table->string('discount_reason')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'discount', 'discount_reason']);
        });
    }
};
