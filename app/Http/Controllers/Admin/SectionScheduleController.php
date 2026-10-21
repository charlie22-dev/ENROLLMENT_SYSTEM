<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionSchedule;
use Illuminate\Http\Request;

class SectionScheduleController extends Controller
{
    public function create(Section $section)
    {
        return view('admin.sections.schedule_form', compact('section'));
    }

    public function store(Request $request, Section $section)
    {
        $data = $request->validate([
            'day'        => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        SectionSchedule::create(array_merge($data, ['section_id' => $section->id]));
        return redirect()->route('admin.sections.edit', $section)->with('success', 'Schedule added.');
    }

    public function edit(SectionSchedule $schedule)
    {
        return view('admin.sections.schedule_form', ['section' => $schedule->section, 'schedule' => $schedule]);
    }

    public function update(Request $request, SectionSchedule $schedule)
    {
        $data = $request->validate([
            'day'        => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);
        $schedule->update($data);
        return redirect()->route('admin.sections.edit', $schedule->section)->with('success', 'Schedule updated.');
    }

    public function destroy(SectionSchedule $schedule)
    {
        $sectionId = $schedule->section_id;
        $schedule->delete();
        return redirect()->route('admin.sections.edit', $sectionId)->with('success', 'Schedule removed.');
    }
}
