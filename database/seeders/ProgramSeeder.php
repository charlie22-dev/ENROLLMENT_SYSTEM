<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $ccs = Department::where('code', 'CCS')->first();
        $coe = Department::where('code', 'COE')->first();

        Program::insert([
            [
                'department_id' => $ccs->id,
                'name'          => 'Bachelor of Science in Information Technology',
                'code'          => 'BSIT',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'department_id' => $ccs->id,
                'name'          => 'Bachelor of Science in Computer Science',
                'code'          => 'BSCS',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'department_id' => $coe->id,
                'name'          => 'Bachelor of Science in Civil Engineering',
                'code'          => 'BSCE',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
