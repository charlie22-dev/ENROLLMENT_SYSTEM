@extends('layouts.app')
@section('title', 'Edit Section')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Section {{ $section->section_code }}</h1>
            <p class="text-sm text-gray-500">{{ $section->subject->code }} — {{ $section->term->label() }}</p>
        </div>
        <a href="{{ route('admin.sections.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.sections.update', $section) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Section Code / Name</label>
                    <input type="text" name="section_code" value="{{ old('section_code', $section->section_code) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Student Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $section->capacity) }}" min="1" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instructor</label>
                    <select name="instructor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">TBA / Unassigned</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" @selected(old('instructor_id', $section->instructor_id) == $inst->id)>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Classroom / Laboratory</label>
                    <select name="room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">TBA / Unassigned</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" @selected(old('room_id', $section->room_id) == $r->id)>{{ $r->name }} ({{ $r->capacity }} cap)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.sections.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Update Section</button>
            </div>
        </form>
    </div>

    {{-- Current Schedules Sub-panel --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-sm">Class Schedules for Section {{ $section->section_code }}</h3>
            <a href="{{ route('admin.sections.schedules.create', $section) }}" class="text-xs font-semibold text-indigo-600 hover:underline">+ Add Schedule Slot</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Day</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Start Time</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">End Time</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($section->schedules as $sched)
                <tr>
                    <td class="px-4 py-2 font-bold">{{ $sched->day }}</td>
                    <td class="px-4 py-2 font-mono text-gray-700">{{ substr($sched->start_time,0,5) }}</td>
                    <td class="px-4 py-2 font-mono text-gray-700">{{ substr($sched->end_time,0,5) }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.schedules.edit', $sched) }}" class="text-xs text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.schedules.destroy', $sched) }}" class="inline" onsubmit="return confirm('Remove schedule?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No schedules configured for this section yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
