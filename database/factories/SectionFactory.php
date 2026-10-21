<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject_id'     => Subject::factory(),
            'term_id'        => Term::factory(),
            'section_code'   => strtoupper(fake()->lexify('?')),
            'capacity'       => 30,
            'enrolled_count' => 0,
            'instructor_id'  => null,
            'room_id'        => null,
        ];
    }
}
