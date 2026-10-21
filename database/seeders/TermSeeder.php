<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        Term::create([
            'school_year'      => '2024-2025',
            'semester'         => '1st',
            'enrollment_start' => '2024-08-01',
            'enrollment_end'   => '2024-08-15',
            'is_active'        => false,
        ]);

        Term::create([
            'school_year'      => '2024-2025',
            'semester'         => '2nd',
            'enrollment_start' => now()->subDays(5)->toDateString(),
            'enrollment_end'   => now()->addDays(30)->toDateString(),
            'is_active'        => true,
        ]);
    }
}
