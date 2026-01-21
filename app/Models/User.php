<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role(){
        return $this->belongsTo(Role::class);

    }
    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'class_user',
            'user_id',
            'class_id'
        );
    }
    
    public function createdClasses(){
        return $this->hasMany(ClassModel::class,'created_by');
    }

    public function createdSubjects(){

        return $this->hasMany(Subject::class,'created_by');
    }
    public function createdExams(){
    return $this->hasMany(Exam::class,'created_by');
    }

    public function studentExams(){
    return $this->hasMany(StudentExam::class);

    }

    public function isLecturer(){

    return $this->role && $this->role->name ==='lecturer';
    }

    public function isStudent(){

    return $this->role && $this->role->name === 'student';
    }

}
