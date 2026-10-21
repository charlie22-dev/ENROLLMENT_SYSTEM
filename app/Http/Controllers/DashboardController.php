<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'student'   => redirect()->route('student.dashboard'),
            'adviser'   => redirect()->route('adviser.queue'),
            'admission' => redirect()->route('admission.queue'),
            'registrar' => redirect()->route('registrar.queue'),
            'cashier'   => redirect()->route('cashier.queue'),
            'admin'     => redirect()->route('admin.dashboard'),
            default     => abort(403),
        };
    }
}
