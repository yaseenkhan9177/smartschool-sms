<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_code')->unique(); // FAM-0001, FAM-0002...
            $table->string('father_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->timestamps();

            // Uniqueness: one family per email per school
            $table->unique(['email', 'school_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
