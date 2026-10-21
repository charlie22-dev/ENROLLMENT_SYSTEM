<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pef;
use App\Models\Student;
use App\Models\Term;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => Student::count(),
            'total_users'     => User::count(),
            'active_term'     => Term::active()->first(),
            'pefs_today'      => Pef::whereDate('created_at', today())->count(),
            'enrolled_count'  => Pef::where('status', 'enrolled')->count(),
            'pending_count'   => Pef::whereIn('status', [
                'pending_advising', 'pending_admission', 'pending_registrar', 'pending_cashier'
            ])->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
