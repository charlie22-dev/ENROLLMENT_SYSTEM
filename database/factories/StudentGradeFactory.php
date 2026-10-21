<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentGradeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'term_id'    => Term::factory(),
            'grade'      => fake()->randomFloat(2, 1.0, 3.0),
            'remarks'    => 'passed',
        ];
    }
}
