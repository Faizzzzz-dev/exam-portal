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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('duration_minutes');
            $table->integer('total_marks')->default(0);
            $table->integer('passing_marks')->default(0);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('shuffle_questions')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
