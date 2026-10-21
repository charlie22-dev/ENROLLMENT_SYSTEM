<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Collection;

class AcademicHistoryService
{
    public function getHistory(Student $student): Collection
    {
        return $student->grades()
            ->with(['subject', 'term'])
            ->get()
            ->groupBy(fn($grade) => $grade->term->label());
    }

    public function getFlaggedSubjects(Student $student): array
    {
        $grades = $student->grades()->with('subject')->get();

        return [
            'failed'  => $grades->where('remarks', 'failed')->values(),
            'inc'     => $grades->where('remarks', 'inc')->values(),
            'dropped' => $grades->where('remarks', 'dropped')->values(),
        ];
    }
}
