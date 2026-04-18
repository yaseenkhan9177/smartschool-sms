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
        // 1. Upgrade exam_terms
        Schema::table('exam_terms', function (Blueprint $table) {
            $table->text('rules')->nullable()->after('end_date');
        });

        // 2. Upgrade exam_schedules
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->string('section')->nullable()->after('class_id'); // For "Class & Section"
            $table->string('paper_type')->default('Theory')->after('subject_id'); // Theory, Practical, Viva
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('room');
            $table->integer('total_marks')->default(100)->after('end_time');
            $table->integer('passing_marks')->default(33)->after('total_marks');
            $table->text('description')->nullable()->after('passing_marks'); // Subject instructions
            $table->string('publish_status')->default('draft')->after('is_published'); // draft, staff, published
            $table->boolean('is_locked')->default(false)->after('publish_status');

            // Foreign key for supervisor (Teacher)
            $table->foreign('supervisor_id')->references('id')->on('teachers')->onDelete('set null');
        });

        // 3. Create exam_term_classes pivot
        Schema::create('exam_term_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_term_id')->constrained('exam_terms')->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['exam_term_id', 'school_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_term_classes');

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn([
                'section',
                'paper_type',
                'supervisor_id',
                'total_marks',
                'passing_marks',
                'description',
                'publish_status',
                'is_locked'
            ]);
        });

        Schema::table('exam_terms', function (Blueprint $table) {
            $table->dropColumn('rules');
        });
    }
};
