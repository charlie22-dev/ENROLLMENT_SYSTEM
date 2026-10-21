<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::latest()->paginate(20);
        return view('admin.terms.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.terms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_year'      => 'required|string|max:20',
            'semester'         => 'required|in:1st,2nd,summer',
            'enrollment_start' => 'required|date',
            'enrollment_end'   => 'required|date|after:enrollment_start',
            'is_active'        => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Term::create($data);
        return redirect()->route('admin.terms.index')->with('success', 'Term created.');
    }

    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term)
    {
        $data = $request->validate([
            'school_year'      => 'required|string|max:20',
            'semester'         => 'required|in:1st,2nd,summer',
            'enrollment_start' => 'required|date',
            'enrollment_end'   => 'required|date|after:enrollment_start',
            'is_active'        => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $term->update($data);
        return redirect()->route('admin.terms.index')->with('success', 'Term updated.');
    }

    public function destroy(Term $term)
    {
        $term->delete();
        return redirect()->route('admin.terms.index')->with('success', 'Term deleted.');
    }

    public function activate(Term $term)
    {
        Term::query()->update(['is_active' => false]);
        $term->update(['is_active' => true]);
        return redirect()->route('admin.terms.index')->with('success', "Term '{$term->label()}' is now active.");
    }
}
