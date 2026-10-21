<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class PrerequisiteController extends Controller
{
    public function index(Subject $subject)
    {
        $subject->load('prerequisites');
        $allSubjects = Subject::where('id', '!=', $subject->id)->get();
        return view('admin.subjects.prerequisites', compact('subject', 'allSubjects'));
    }

    public function store(Request $request, Subject $subject)
    {
        $request->validate([
            'prerequisite_id' => 'required|exists:subjects,id|different:subject_id',
        ]);
        $subject->prerequisites()->syncWithoutDetaching([$request->prerequisite_id]);
        return redirect()->back()->with('success', 'Prerequisite added.');
    }

    public function destroy(Subject $subject, Subject $prerequisite)
    {
        $subject->prerequisites()->detach($prerequisite->id);
        return redirect()->back()->with('success', 'Prerequisite removed.');
    }
}
