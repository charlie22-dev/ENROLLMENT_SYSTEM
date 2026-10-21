<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bsit = Program::where('code', 'BSIT')->first();

        User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $adviser = User::create([
            'name'     => 'Prof. Maria Santos',
            'email'    => 'adviser@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'adviser',
        ]);

        User::create([
            'name'     => 'Admission Officer',
            'email'    => 'admission@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'admission',
        ]);

        User::create([
            'name'     => 'University Registrar',
            'email'    => 'registrar@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'registrar',
        ]);

        User::create([
            'name'     => 'Cashier Office',
            'email'    => 'cashier@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'cashier',
        ]);

        $studentUser = User::create([
            'name'     => 'Juan dela Cruz',
            'email'    => 'student@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        Student::create([
            'user_id'    => $studentUser->id,
            'student_no' => '2024-0001',
            'program_id' => $bsit->id,
            'year_level' => 1,
            'adviser_id' => $adviser->id,
            'status'     => 'active',
        ]);

        $studentUser2 = User::create([
            'name'     => 'Maria Reyes',
            'email'    => 'student2@school.edu',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        Student::create([
            'user_id'    => $studentUser2->id,
            'student_no' => '2024-0002',
            'program_id' => $bsit->id,
            'year_level' => 1,
            'adviser_id' => $adviser->id,
            'status'     => 'active',
        ]);
    }
}
