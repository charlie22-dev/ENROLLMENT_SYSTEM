<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Seeder;

class StudentGradeSeeder extends Seeder
{
    public function run(): void
    {
        $pastTerm = Term::where('is_active', false)->first();
        $student  = Student::whereHas('user', fn($q) => $q->where('email', 'student@school.edu'))->first();

        $grades = [
            ['IT101', 1.25, 'passed'],
            ['IT102', 1.50, 'passed'],
            ['IT103', 1.75, 'passed'],
            ['IT104', 1.25, 'passed'],
            ['IT201', 4.00, 'failed'],
            ['IT203', null, 'inc'],
            ['IT205', null, 'dropped'],
        ];

        foreach ($grades as [$code, $grade, $remarks]) {
            $subject = Subject::where('code', $code)->first();
            if ($subject && $student) {
                StudentGrade::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'term_id'    => $pastTerm->id,
                    'grade'      => $grade,
                    'remarks'    => $remarks,
                ]);
            }
        }

        $student2 = Student::whereHas('user', fn($q) => $q->where('email', 'student2@school.edu'))->first();
        $passingSubjects = ['IT101', 'IT102', 'IT103', 'IT104'];
        foreach ($passingSubjects as $code) {
            $subject = Subject::where('code', $code)->first();
            if ($subject && $student2) {
                StudentGrade::create([
                    'student_id' => $student2->id,
                    'subject_id' => $subject->id,
                    'term_id'    => $pastTerm->id,
                    'grade'      => 1.50,
                    'remarks'    => 'passed',
                ]);
            }
        }
    }
}
