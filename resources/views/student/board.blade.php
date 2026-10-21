@extends('layouts.app')
@section('title', 'Offered Sections Board')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Offered Sections ("The Board")</h1>
            @if($activeTerm)
                <p class="text-sm text-gray-500">Active Term: <strong class="text-indigo-600">{{ $activeTerm->label() }}</strong></p>
            @endif
        </div>
        <a href="{{ route('student.pef.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow">
            Go to PEF Builder →
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 flex gap-4 flex-wrap items-center">
        <div class="flex-1 min-w-[240px]">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by subject code (e.g. IT101) or title..."
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <select name="day" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Days</option>
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                    <option value="{{ $day }}" @selected(request('day') === $day)>{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Filter</button>
        <a href="{{ route('student.board') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Reset</a>
    </form>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sec</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Units</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Schedule</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Room</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Instructor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Seats</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sections as $section)
                <tr class="{{ $section->isFull() ? 'bg-gray-50 opacity-60' : 'hover:bg-indigo-50/30' }}">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900">{{ $section->subject->code }}</div>
                        <div class="text-xs text-gray-500">{{ $section->subject->title }}</div>
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $section->section_code }}</td>
                    <td class="px-4 py-3">{{ $section->subject->units }} ({{ strtoupper($section->subject->type) }})</td>
                    <td class="px-4 py-3">
                        @foreach($section->schedules as $sched)
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded font-mono mr-1 mb-1">
                                {{ $sched->day }} {{ substr($sched->start_time, 0, 5) }}–{{ substr($sched->end_time, 0, 5) }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $section->room?->name ?? 'TBA' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $section->instructor?->name ?? 'TBA' }}</td>
                    <td class="px-4 py-3">
                        @if($section->isFull())
                            <span class="inline-flex px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-full">Full</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-full">
                                {{ $section->availableSlots() }} left
                            </span>
                        @endif
                        <span class="text-xs text-gray-400 block mt-0.5">{{ $section->enrolled_count }} / {{ $section->capacity }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">No sections found for this term.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sections->links() }}
    </div>
</div>
@endsection
