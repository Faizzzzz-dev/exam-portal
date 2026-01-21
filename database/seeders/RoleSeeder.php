<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            'name' => 'lecturer',
            'description' => 'Lecturer role with full access to create and manage exams'
        ]);

        Role::create([
            'name' => 'student',
            'description' => 'Student role with access to take assigned exams'
        ]);
    }
}
