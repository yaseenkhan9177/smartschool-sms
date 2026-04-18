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
        if (!Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->enum('status', ['active', 'suspended', 'pending'])->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('school_requests')) {
            Schema::create('school_requests', function (Blueprint $table) {
                $table->id();
                $table->string('school_name');
                $table->string('owner_name');
                $table->string('email');
                $table->string('phone');
                $table->text('address');
                $table->string('city');
                $table->integer('student_count')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_requests');
        Schema::dropIfExists('schools');
    }
};
