<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::insert([
            ['name' => 'College of Computer Studies',  'code' => 'CCS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'College of Engineering',       'code' => 'COE', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
