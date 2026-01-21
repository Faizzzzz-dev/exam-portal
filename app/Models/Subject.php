<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'class_subject', // pivot table
            'subject_id',    // foreign key for this model (Subject)
            'class_id'       // foreign key for related model (ClassModel)
        );
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
