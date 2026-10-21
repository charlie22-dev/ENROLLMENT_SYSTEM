<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_no', 30)->unique();
            $table->foreignId('program_id')->constrained();
            $table->unsignedTinyInteger('year_level')->default(1);
            $table->unsignedBigInteger('adviser_id')->nullable();
            $table->foreign('adviser_id')->references('id')->on('users')->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
