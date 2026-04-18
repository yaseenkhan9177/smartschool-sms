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
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained('exam_terms')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            // Assuming simplified subject linking for now (either by name or ID if table exists)
            // Existing 'subjects' table was seen in file list, so let's try to link it if possible, 
            // OR use foreignId if 'subjects' implies a table. Earlier file list confirmed 2025_11_23_164800_create_subjects_table.php
            // So we will use foreignId('subject_id').
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('file_path');
            $table->enum('status', ['submitted', 'approved', 'printed'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};
