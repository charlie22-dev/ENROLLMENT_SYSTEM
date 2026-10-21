<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TermFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_year'      => '2024-2025',
            'semester'         => '1st',
            'enrollment_start' => now()->subMonth(),
            'enrollment_end'   => now()->addMonth(),
            'is_active'        => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn($attr) => ['is_active' => true]);
    }
}
