<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'start_time',
        'end_time',
        'shuffle_questions',
        'max_attempts',
        'is_published',
        'created_by',
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'shuffle_questions' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function questions(){
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(ExamAssignment::class);
    }

    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'exam_assignments',
            'exam_id',
            'class_id'
        );
    }
    

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class);
    }

    public function isAvailable(){

        $now =Carbon::now();

        if(!$this->is_published){
            return false;
        }

        if ($this->start_time && $now->lt($this->start_time)) {
            return false;
        }
        if ($this->end_time && $now->gt($this->end_time)) {
            return false;
        }

        return true;
    }

    public function canStudentAttempt($userId)
    {
        $attempts = $this->studentExams()
            ->where('user_id', $userId)
            ->where('status', '!=', 'in_progress')
            ->count();

        return $attempts < $this->max_attempts;
    }


}
