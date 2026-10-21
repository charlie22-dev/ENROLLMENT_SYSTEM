@extends('layouts.app')
@section('title', isset($schedule) ? 'Edit Schedule Slot' : 'Add Schedule Slot')
@section('content')
<div class="max-w-md mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ isset($schedule) ? 'Edit Schedule Slot' : 'Add Schedule Slot' }}</h1>
            <p class="text-xs text-gray-500">Section {{ $section->section_code }} — {{ $section->subject->code }}</p>
        </div>
        <a href="{{ route('admin.sections.edit', $section) }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ isset($schedule) ? route('admin.schedules.update', $schedule) : route('admin.sections.schedules.store', $section) }}" class="space-y-4">
            @csrf
            @if(isset($schedule)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Day of the Week</label>
                <select name="day" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                        <option value="{{ $d }}" @selected(old('day', $schedule->day ?? '') === $d)>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Start Time (24h)</label>
                    <input type="text" name="start_time" value="{{ old('start_time', isset($schedule) ? substr($schedule->start_time,0,5) : '08:00') }}" placeholder="08:00" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">End Time (24h)</label>
                    <input type="text" name="end_time" value="{{ old('end_time', isset($schedule) ? substr($schedule->end_time,0,5) : '09:30') }}" placeholder="09:30" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.sections.edit', $section) }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
                    {{ isset($schedule) ? 'Update Slot' : 'Save Slot' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
