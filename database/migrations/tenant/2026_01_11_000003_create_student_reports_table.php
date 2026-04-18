<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            // Reporter can be a User (Admin), Teacher, or Accountant. Using polymorphic or just ID + Role string
            // adhering to user request: "Teacher or Accountant logs in"
            $table->unsignedBigInteger('reporter_id');
            $table->string('reporter_role'); // 'teacher', 'accountant', 'admin'
            $table->string('severity'); // 'low', 'medium', 'high'
            $table->text('reason');
            $table->string('status')->default('pending'); // 'pending', 'resolved', 'escalated'
            $table->text('resolution_note')->nullable(); // For admin to add notes when resolving/escalating
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_reports');
    }
};
