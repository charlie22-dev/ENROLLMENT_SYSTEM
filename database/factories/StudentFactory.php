<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'    => User::factory()->withRole('student'),
            'student_no' => fake()->unique()->numerify('20##-####'),
            'program_id' => Program::factory(),
            'year_level' => fake()->numberBetween(1, 4),
            'adviser_id' => null,
            'status'     => 'active',
        ];
    }
}
