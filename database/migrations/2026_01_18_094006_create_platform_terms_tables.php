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
        if (!Schema::hasTable('platform_terms')) {
            Schema::create('platform_terms', function (Blueprint $table) {
                $table->id();
                $table->longText('content');
                $table->string('version')->default('1.0');
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('platform_terms_acceptance')) {
            Schema::create('platform_terms_acceptance', function (Blueprint $table) {
                $table->id();
                $table->string('school_email')->index(); // or link to request_id later
                $table->timestamp('accepted_at');
                $table->string('ip_address')->nullable();
                $table->string('terms_version');
                $table->unsignedBigInteger('request_id')->nullable()->index(); // Link to SchoolRequest
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_terms_acceptance');
        Schema::dropIfExists('platform_terms');
    }
};
