<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            ProgramSeeder::class,
            SubjectSeeder::class,
            CurriculumSubjectSeeder::class,
            TermSeeder::class,
            RoomSeeder::class,
            InstructorSeeder::class,
            SectionSeeder::class,
            UserSeeder::class,
            StudentGradeSeeder::class,
        ]);
    }
}
