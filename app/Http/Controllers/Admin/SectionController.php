<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Room;
use App\Models\Section;
use App\Models\SectionSchedule;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::with(['subject', 'term', 'instructor', 'room'])
            ->when($request->term_id, fn($q) => $q->where('term_id', $request->term_id))
            ->paginate(20);

        $terms = Term::orderByDesc('id')->get();
        return view('admin.sections.index', compact('sections', 'terms'));
    }

    public function create()
    {
        $subjects    = Subject::orderBy('code')->get();
        $terms       = Term::orderByDesc('id')->get();
        $instructors = Instructor::orderBy('name')->get();
        $rooms       = Room::orderBy('name')->get();
        return view('admin.sections.create', compact('subjects', 'terms', 'instructors', 'rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'term_id'       => 'required|exists:terms,id',
            'section_code'  => 'required|string|max:10',
            'capacity'      => 'required|integer|min:1',
            'instructor_id' => 'nullable|exists:instructors,id',
            'room_id'       => 'nullable|exists:rooms,id',
            'schedules'              => 'nullable|array',
            'schedules.*.day'        => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time'   => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        $section = Section::create([
            'subject_id'    => $data['subject_id'],
            'term_id'       => $data['term_id'],
            'section_code'  => $data['section_code'],
            'capacity'      => $data['capacity'],
            'instructor_id' => $data['instructor_id'] ?? null,
            'room_id'       => $data['room_id'] ?? null,
        ]);

        foreach ($data['schedules'] ?? [] as $schedule) {
            SectionSchedule::create(array_merge($schedule, ['section_id' => $section->id]));
        }

        return redirect()->route('admin.sections.index')->with('success', 'Section created.');
    }

    public function edit(Section $section)
    {
        $section->load('schedules');
        $subjects    = Subject::orderBy('code')->get();
        $terms       = Term::orderByDesc('id')->get();
        $instructors = Instructor::orderBy('name')->get();
        $rooms       = Room::orderBy('name')->get();
        return view('admin.sections.edit', compact('section', 'subjects', 'terms', 'instructors', 'rooms'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'section_code'  => 'required|string|max:10',
            'capacity'      => 'required|integer|min:1',
            'instructor_id' => 'nullable|exists:instructors,id',
            'room_id'       => 'nullable|exists:rooms,id',
            'schedules'              => 'nullable|array',
            'schedules.*.day'        => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time'   => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        $section->update([
            'section_code'  => $data['section_code'],
            'capacity'      => $data['capacity'],
            'instructor_id' => $data['instructor_id'] ?? null,
            'room_id'       => $data['room_id'] ?? null,
        ]);

        if (isset($data['schedules'])) {
            $section->schedules()->delete();
            foreach ($data['schedules'] as $schedule) {
                SectionSchedule::create(array_merge($schedule, ['section_id' => $section->id]));
            }
        }

        return redirect()->route('admin.sections.index')->with('success', 'Section updated.');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted.');
    }
}
