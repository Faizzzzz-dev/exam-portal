<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAssignment extends Model
{
    //
    protected $fillable = [
        'exam_id',
        'class_id',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}
