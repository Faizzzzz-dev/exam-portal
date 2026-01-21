<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ClassModel;
use App\Models\Subject;





class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Get roles
         $lecturerRole = Role::where('name', 'lecturer')->first();
         $studentRole = Role::where('name', 'student')->first();
 
         // Create lecturer
         $lecturer = User::create([
             'name' => 'Dr. John Smith',
             'email' => 'lecturer@example.com',
             'password' => Hash::make('password'),
             'role_id' => $lecturerRole->id,
         ]);
 
         // Create students
         $students = [];
         for ($i = 1; $i <= 5; $i++) {
             $students[] = User::create([
                 'name' => 'Student ' . $i,
                 'email' => 'student' . $i . '@example.com',
                 'password' => Hash::make('password'),
                 'role_id' => $studentRole->id,
             ]);
         }
 
         // Create classes
         $class1 = ClassModel::create([
             'name' => 'Computer Science 101',
             'code' => 'CS101',
             'description' => 'Introduction to Computer Science',
             'created_by' => $lecturer->id,
         ]);
 
         $class2 = ClassModel::create([
             'name' => 'Mathematics Advanced',
             'code' => 'MATH201',
             'description' => 'Advanced Mathematics Course',
             'created_by' => $lecturer->id,
         ]);
 
         // Assign students to classes
         $class1->students()->attach([$students[0]->id, $students[1]->id, $students[2]->id]);
         $class2->students()->attach([$students[2]->id, $students[3]->id, $students[4]->id]);
 
         // Create subjects
         $subject1 = Subject::create([
             'name' => 'Programming Fundamentals',
             'code' => 'PROG101',
             'description' => 'Basic programming concepts',
             'created_by' => $lecturer->id,
         ]);
 
         $subject2 = Subject::create([
             'name' => 'Calculus',
             'code' => 'CALC101',
             'description' => 'Introduction to Calculus',
             'created_by' => $lecturer->id,
         ]);
 
         // Link subjects to classes
         $class1->subjects()->attach($subject1->id);
         $class2->subjects()->attach($subject2->id);
    }
}
