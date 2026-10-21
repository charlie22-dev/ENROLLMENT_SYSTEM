<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $ccs = Department::where('code', 'CCS')->first();

        $instructors = [
            ['name' => 'Dr. Maria Santos',     'email' => 'msantos@school.edu',   'department_id' => $ccs->id],
            ['name' => 'Prof. Juan Dela Cruz', 'email' => 'jdelacruz@school.edu', 'department_id' => $ccs->id],
            ['name' => 'Dr. Ana Reyes',        'email' => 'areyes@school.edu',    'department_id' => $ccs->id],
            ['name' => 'Prof. Carlos Gomez',   'email' => 'cgomez@school.edu',    'department_id' => $ccs->id],
        ];

        foreach ($instructors as $instructor) {
            Instructor::create(array_merge($instructor, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
