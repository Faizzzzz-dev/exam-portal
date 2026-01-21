<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentExam extends Model
{
    //
    protected $fillable = [
        'user_id',
        'exam_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'score',
        'total_marks',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function getRemainingTimeAttribute()
    {
        if (!$this->started_at || $this->status !== 'in_progress') {
            return 0;
        }

        $elapsed = Carbon::now()->diffInMinutes($this->started_at);
        $remaining = $this->exam->duration_minutes - $elapsed;

        return max(0, $remaining);
    }

    public function isExpired()
    {
        if (!$this->started_at || $this->status !== 'in_progress') {
            return false;
        }

        $elapsed = Carbon::now()->diffInMinutes($this->started_at);
        return $elapsed >= $this->exam->duration_minutes;
    }

    public function getPercentageAttribute()
    {
        if (!$this->total_marks || $this->total_marks == 0) {
            return 0;
        }

        return round(($this->score / $this->total_marks) * 100, 2);
    }
}
