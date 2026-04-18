<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('parents')) {
            Schema::create('parents', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone'); // Login Username
                $table->string('password'); // Login Password
                $table->string('cnic')->nullable(); // National ID
                $table->text('address')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();

                // Unique phone constraint per school to prevent login conflicts

                // Foreign key for school
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('parents');
    }
};
