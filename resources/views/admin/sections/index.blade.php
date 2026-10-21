@extends('layouts.app')
@section('title', 'Sections')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Course Sections & Offerings</h1>
            <p class="text-sm text-gray-500">Manage offered class sections, capacities, instructors, and rooms</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Section
        </a>
    </div>

    {{-- Filter by term --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 flex gap-4 items-center">
        <label class="text-sm font-semibold text-gray-700">Filter by Term:</label>
        <select name="term_id" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm" onchange="this.form.submit()">
            <option value="">All Terms</option>
            @foreach($terms as $t)
                <option value="{{ $t->id }}" @selected(request('term_id') == $t->id)>{{ $t->label() }} {{ $t->is_active ? '(ACTIVE)' : '' }}</option>
            @endforeach
        </select>
        @if(request('term_id'))
            <a href="{{ route('admin.sections.index') }}" class="text-xs text-indigo-600 hover:underline">Clear Filter</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sec</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Term</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Schedule</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Room</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Instructor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slots</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sections as $sec)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="font-bold text-indigo-700 block">{{ $sec->subject->code }}</span>
                        <span class="text-xs text-gray-500">{{ $sec->subject->title }}</span>
                    </td>
                    <td class="px-4 py-3 font-bold">{{ $sec->section_code }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $sec->term->school_year }} {{ $sec->term->semester }}</td>
                    <td class="px-4 py-3 text-xs">
                        @foreach($sec->schedules as $sched)
                            <div class="font-mono text-gray-700">{{ $sched->day }} {{ substr($sched->start_time,0,5) }}–{{ substr($sched->end_time,0,5) }}</div>
                        @endforeach
                        <a href="{{ route('admin.sections.schedules.create', $sec) }}" class="text-[11px] text-indigo-600 hover:underline block mt-1">+ Add Slot</a>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $sec->room?->name ?? 'TBA' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $sec->instructor?->name ?? 'TBA' }}</td>
                    <td class="px-4 py-3">
                        <span class="font-semibold {{ $sec->isFull() ? 'text-red-600' : 'text-green-700' }}">{{ $sec->enrolled_count }} / {{ $sec->capacity }}</span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.sections.edit', $sec) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.sections.destroy', $sec) }}" class="inline" onsubmit="return confirm('Delete this section?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No sections found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sections->links() }}
</div>
@endsection
