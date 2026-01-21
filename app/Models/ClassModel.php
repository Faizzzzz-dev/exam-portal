<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    //
    protected $table ='classes';
    protected $fillable =[
        'name',
        'code',
        'description',
        'created_by',

    ];

    public function creator(){

        return $this->belongsTo(User::class,'created_by');
    }

    public function subjects(){
        return $this->belongsToMany(
            Subject::class,
            'class_subject', // pivot table
            'class_id',      // foreign key for ClassModel
            'subject_id'     // foreign key for Subject
        );
    }
    

    public function students(){
        return $this->belongsToMany(
            User::class,
            'class_user', // pivot table
            'class_id',   // foreign key for ClassModel
            'user_id'     // foreign key for User
        );
    }
    

    public function examAssignments(){
        return $this->hasMany(ExamAssignment::class,'class_id');
    }

    public function exams()
{
    return $this->belongsToMany(
        Exam::class,
        'exam_assignments',
        'class_id',
        'exam_id'
    );
}


}
