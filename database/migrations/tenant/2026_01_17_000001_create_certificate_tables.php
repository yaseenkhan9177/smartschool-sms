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
        // 1. Certificate Types (e.g., Leaving, Character)
        Schema::create('certificate_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Leaving Certificate"
            $table->timestamps();
        });

        // 2. Certificate Templates (Design & Layout)
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('certificate_types')->onDelete('cascade');
            $table->string('title'); // e.g., "SCHOOL LEAVING CERTIFICATE"
            $table->text('body'); // Content with placeholders like {{student_name}}
            $table->string('footer_left')->nullable(); // e.g., "Principal Signature"
            $table->string('footer_right')->nullable(); // e.g., "Date & Stamp"
            $table->string('background_image')->nullable(); // For custom background
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Issued Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certificate_templates')->onDelete('cascade');
            $table->string('certificate_no')->unique(); // e.g., CERT-2024-001
            $table->date('issue_date');
            $table->enum('status', ['draft', 'issued'])->default('draft');
            $table->unsignedBigInteger('issued_by')->nullable(); // Admin ID
            $table->json('data_snapshot')->nullable(); // Store the exact data printed (in case student data changes later)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('certificate_types');
    }
};
