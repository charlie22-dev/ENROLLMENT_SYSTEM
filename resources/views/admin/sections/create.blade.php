@extends('layouts.app')
@section('title', 'Add Section')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add New Section</h1>
        <a href="{{ route('admin.sections.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.sections.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Subject</label>
                    <select name="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" @selected(old('subject_id') == $s->id)>{{ $s->code }} — {{ $s->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Term</label>
                    <select name="term_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">Select Term</option>
                        @foreach($terms as $t)
                            <option value="{{ $t->id }}" @selected(old('term_id') == $t->id || $t->is_active)>{{ $t->label() }} {{ $t->is_active ? '(ACTIVE)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Section Code / Name</label>
                    <input type="text" name="section_code" value="{{ old('section_code', 'A') }}" placeholder="e.g. A, B, or 101" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Student Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 35) }}" min="1" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instructor</label>
                    <select name="instructor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">TBA / Unassigned</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" @selected(old('instructor_id') == $inst->id)>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Classroom / Laboratory</label>
                    <select name="room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">TBA / Unassigned</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" @selected(old('room_id') == $r->id)>{{ $r->name }} ({{ $r->capacity }} cap)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t">
                <h3 class="font-bold text-gray-800 text-sm mb-3">Initial Schedule Slot (Optional)</h3>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Day</label>
                        <select name="schedules[0][day]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Start Time (24h)</label>
                        <input type="text" name="schedules[0][start_time]" placeholder="08:00" value="08:00" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">End Time (24h)</label>
                        <input type="text" name="schedules[0][end_time]" placeholder="09:30" value="09:30" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs font-mono">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.sections.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Save Section</button>
            </div>
        </form>
    </div>
</div>
@endsection
