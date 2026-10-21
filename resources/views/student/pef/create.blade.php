@extends('layouts.app')
@section('title', 'Build Pre-Enrollment Form')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pre-Enrollment Form (PEF) Builder</h1>
            <p class="text-sm text-gray-500">Pick sections for <strong class="text-indigo-600">{{ $activeTerm->label() }}</strong></p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Dashboard</a>
    </div>

    <form method="POST" action="{{ route('student.pef.store') }}" id="pef-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Available sections selector --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Available Sections</h2>
                    <span class="text-xs text-gray-500">Select checkboxes to pick subjects</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center">Select</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Schedule</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Room</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slots</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sections as $section)
                            <tr class="{{ $section->isFull() ? 'opacity-40 bg-gray-50' : 'hover:bg-indigo-50/20' }}">
                                <td class="px-4 py-3 text-center">
                                    <input type="checkbox"
                                           name="section_ids[]"
                                           value="{{ $section->id }}"
                                           class="section-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                           data-code="{{ $section->subject->code }}"
                                           data-sec="{{ $section->section_code }}"
                                           data-units="{{ $section->subject->units }}"
                                           {{ $section->isFull() ? 'disabled' : '' }}>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $section->subject->code }} - Sec {{ $section->section_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $section->subject->title }} ({{ $section->subject->units }} units)</div>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @foreach($section->schedules as $s)
                                        <div class="font-mono text-gray-700">{{ $s->day }} {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $section->room?->name ?? 'TBA' }}</td>
                                <td class="px-4 py-3 text-xs">
                                    @if($section->isFull())
                                        <span class="text-red-500 font-bold">Full</span>
                                    @else
                                        <span class="text-green-600 font-semibold">{{ $section->availableSlots() }} left</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow p-5 sticky top-20">
                    <h2 class="font-bold text-gray-800 pb-3 border-b mb-3">Selected Sections Summary</h2>

                    <ul id="selected-list" class="space-y-2 text-sm text-gray-700 min-h-[120px] mb-4">
                        <li class="text-gray-400 italic text-center py-6" id="empty-msg">No sections selected yet.</li>
                    </ul>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Total Units:</span>
                            <span id="total-units" class="text-xl font-extrabold text-indigo-700">0</span>
                        </div>
                        <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded">
                            <span>Allowed range: <strong>{{ config('fees.min_units', 12) }}</strong> to <strong>{{ config('fees.max_units', 25) }}</strong> units.</span>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                        Save PEF Draft
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-2">After saving as draft, you can review schedule conflicts and officially submit for advising.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.section-checkbox');
    const selectedList = document.getElementById('selected-list');
    const totalUnitsEl = document.getElementById('total-units');

    function updateSummary() {
        const checked = [...checkboxes].filter(c => c.checked);
        const total = checked.reduce((sum, c) => sum + parseInt(c.dataset.units), 0);
        totalUnitsEl.textContent = total;

        selectedList.innerHTML = '';
        if (checked.length === 0) {
            selectedList.innerHTML = '<li class="text-gray-400 italic text-center py-6">No sections selected yet.</li>';
        } else {
            checked.forEach(c => {
                const li = document.createElement('li');
                li.className = 'flex justify-between items-center bg-indigo-50/50 p-2 rounded border border-indigo-100 text-xs';
                li.innerHTML = `<span><strong class="text-indigo-900">${c.dataset.code}</strong> (${c.dataset.sec})</span><span class="font-semibold text-gray-700">${c.dataset.units} u</span>`;
                selectedList.appendChild(li);
            });
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));
</script>
@endsection
