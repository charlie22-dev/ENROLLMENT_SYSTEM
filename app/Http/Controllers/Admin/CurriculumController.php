<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurriculumSubject;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        $programs = Program::with(['curriculumSubjects.subject'])->get();
        return view('admin.curriculum.index', compact('programs'));
    }

    public function create()
    {
        $programs  = Program::all();
        $subjects  = Subject::orderBy('code')->get();
        return view('admin.curriculum.create', compact('programs', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'subject_id' => 'required|exists:subjects,id',
            'year_level' => 'required|integer|between:1,5',
            'term_no'    => 'required|integer|between:1,3',
        ]);

        CurriculumSubject::firstOrCreate(
            ['program_id' => $data['program_id'], 'subject_id' => $data['subject_id']],
            ['year_level' => $data['year_level'], 'term_no' => $data['term_no']]
        );

        return redirect()->route('admin.curriculum.index')->with('success', 'Subject added to curriculum.');
    }

    public function edit(CurriculumSubject $curriculum)
    {
        $programs = Program::all();
        $subjects = Subject::orderBy('code')->get();
        return view('admin.curriculum.edit', compact('curriculum', 'programs', 'subjects'));
    }

    public function update(Request $request, CurriculumSubject $curriculum)
    {
        $data = $request->validate([
            'year_level' => 'required|integer|between:1,5',
            'term_no'    => 'required|integer|between:1,3',
        ]);
        $curriculum->update($data);
        return redirect()->route('admin.curriculum.index')->with('success', 'Curriculum entry updated.');
    }

    public function destroy(CurriculumSubject $curriculum)
    {
        $curriculum->delete();
        return redirect()->route('admin.curriculum.index')->with('success', 'Subject removed from curriculum.');
    }
}
