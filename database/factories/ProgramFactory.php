<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'name'          => 'Bachelor of Science in ' . fake()->unique()->word(),
            'code'          => strtoupper(fake()->unique()->lexify('BS??')),
        ];
    }
}
