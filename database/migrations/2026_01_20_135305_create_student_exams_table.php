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
        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->integer('attempt_number')->default(1);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('total_marks')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->timestamps();

            $table->index(['user_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exams');
    }
};
