<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'  => strtoupper(fake()->unique()->lexify('IT???')),
            'title' => fake()->words(3, true),
            'units' => fake()->numberBetween(1, 3),
            'type'  => fake()->randomElement(['lec', 'lab']),
        ];
    }
}
