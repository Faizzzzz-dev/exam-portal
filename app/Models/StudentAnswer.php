<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    //
    protected $fillable = [
        'student_exam_id',
        'question_id',
        'selected_option_id',
        'text_answer',
        'marks_obtained',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function studentExam()
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}
