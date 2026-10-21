<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Term;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $activeTerm = Term::active()->first();

        if (! $activeTerm) {
            return view('student.board', ['sections' => collect(), 'activeTerm' => null]);
        }

        $query = Section::with(['subject', 'schedules', 'instructor', 'room'])
            ->where('term_id', $activeTerm->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('subject', fn($q) => $q
                ->where('code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
            );
        }

        if ($request->filled('day')) {
            $query->whereHas('schedules', fn($q) => $q->where('day', $request->day));
        }

        $sections = $query->paginate(20)->withQueryString();

        return view('student.board', compact('sections', 'activeTerm'));
    }
}
