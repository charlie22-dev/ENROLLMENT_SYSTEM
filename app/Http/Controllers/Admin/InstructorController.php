<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::with('department')->paginate(20);
        return view('admin.instructors.index', compact('instructors'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.instructors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:instructors',
            'department_id' => 'nullable|exists:departments,id',
        ]);
        Instructor::create($data);
        return redirect()->route('admin.instructors.index')->with('success', 'Instructor created.');
    }

    public function edit(Instructor $instructor)
    {
        $departments = Department::all();
        return view('admin.instructors.edit', compact('instructor', 'departments'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:instructors,email,' . $instructor->id,
            'department_id' => 'nullable|exists:departments,id',
        ]);
        $instructor->update($data);
        return redirect()->route('admin.instructors.index')->with('success', 'Instructor updated.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return redirect()->route('admin.instructors.index')->with('success', 'Instructor deleted.');
    }
}
