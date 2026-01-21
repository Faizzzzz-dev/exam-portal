<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //

    protected $fillable = [
        'exam_id',
        'type',
        'question_text',
        'marks',
        'order',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function correctOption()
    {
        return $this->hasOne(QuestionOption::class)->where('is_correct', true);
    }
}
