<?php

namespace Database\Seeders;

use App\Models\CurriculumSubject;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class CurriculumSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $bsit = Program::where('code', 'BSIT')->first();

        $curriculum = [
            ['IT101', 1, 1],
            ['IT102', 1, 1],
            ['IT103', 1, 1],
            ['IT104', 1, 1],
            ['IT201', 1, 2],
            ['IT202', 1, 2],
            ['IT203', 1, 2],
            ['IT204', 2, 1],
            ['IT205', 2, 1],
            ['IT301', 2, 2],
            ['IT302', 2, 2],
            ['IT303', 2, 2],
        ];

        foreach ($curriculum as [$code, $year, $term]) {
            $subject = Subject::where('code', $code)->first();
            CurriculumSubject::create([
                'program_id' => $bsit->id,
                'subject_id' => $subject->id,
                'year_level' => $year,
                'term_no'    => $term,
            ]);
        }
    }
}
