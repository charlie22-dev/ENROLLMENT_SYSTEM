<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        $activePef = $student?->activePef();

        if ($activePef) {
            $activePef->load(['items.section.subject', 'items.section.schedules', 'approvals.actor', 'term']);
        }

        $activeTerm = Term::active()->first();

        return view('student.dashboard', compact('activePef', 'activeTerm', 'student'));
    }
}
