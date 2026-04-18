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
        Schema::create('teacher_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('host_id')->nullable(); // Can be admin or teacher
            $table->string('host_type')->nullable(); // 'admin' or 'teacher'
            $table->string('topic');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration'); // in minutes
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_start_url')->nullable();
            $table->text('zoom_join_url')->nullable();
            $table->string('password')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'started', 'ended'])->default('draft');
            $table->timestamps();
        });

        Schema::create('teacher_meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('teacher_meetings')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('status', ['invited', 'attended', 'absent'])->default('invited');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_meeting_participants');
        Schema::dropIfExists('teacher_meetings');
    }
};
