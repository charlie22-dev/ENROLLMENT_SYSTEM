@extends('layouts.app')
@section('title', 'Adviser Review — ' . $pef->student->user->name)
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review Pre-Enrollment: {{ $pef->student->user->name }}</h1>
            <p class="text-sm text-gray-500">Student No: <strong class="text-gray-800">{{ $pef->student->student_no }}</strong> | Program: <strong class="text-gray-800">{{ $pef->student->program->name }}</strong></p>
        </div>
        <x-status-badge :status="$pef->status" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Review Columns --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Flagged Academic History Notice --}}
            @if(count($flagged['failed']) || count($flagged['inc']) || count($flagged['dropped']))
            <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-lg shadow-sm">
                <h3 class="font-bold text-red-900 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Academic Warning: Deficiencies Found in Past Semesters
                </h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    @if(count($flagged['failed']))
                        <div class="bg-white p-3 rounded border border-red-200">
                            <span class="font-bold text-red-700 block mb-1">Failed ({{ count($flagged['failed']) }})</span>
                            <ul class="space-y-1">
                                @foreach($flagged['failed'] as $f)
                                    <li class="font-semibold text-gray-800">{{ $f->subject->code }} ({{ $f->grade }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(count($flagged['inc']))
                        <div class="bg-white p-3 rounded border border-yellow-200">
                            <span class="font-bold text-yellow-700 block mb-1">Incomplete ({{ count($flagged['inc']) }})</span>
                            <ul class="space-y-1">
                                @foreach($flagged['inc'] as $f)
                                    <li class="font-semibold text-gray-800">{{ $f->subject->code }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(count($flagged['dropped']))
                        <div class="bg-white p-3 rounded border border-gray-200">
                            <span class="font-bold text-gray-600 block mb-1">Dropped ({{ count($flagged['dropped']) }})</span>
                            <ul class="space-y-1">
                                @foreach($flagged['dropped'] as $f)
                                    <li class="font-semibold text-gray-800">{{ $f->subject->code }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Selected Sections on this PEF --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Requested Subjects for Enrollment ({{ $pef->total_units }} units)</h2>
                    <span class="text-xs text-gray-500">{{ $pef->term->label() }}</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Sec</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Schedule</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pef->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $item->section->subject->code }}</div>
                                <div class="text-xs text-gray-500">{{ $item->section->subject->title }}</div>
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $item->section->section_code }}</td>
                            <td class="px-4 py-3">{{ $item->section->subject->units }}</td>
                            <td class="px-4 py-3 text-xs">
                                @foreach($item->section->schedules as $s)
                                    <div class="font-mono text-gray-700">{{ $s->day }} {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Academic History Table By Term --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50">
                    <h2 class="font-bold text-gray-800">Student Academic History (Past Semesters)</h2>
                </div>
                <div class="p-5 space-y-6">
                    @forelse($history as $termLabel => $grades)
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2 font-semibold text-xs text-gray-700 uppercase tracking-wider">
                            {{ $termLabel }}
                        </div>
                        <table class="min-w-full text-sm divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 uppercase">Code</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 uppercase">Title</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 uppercase">Grade</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 uppercase">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($grades as $grade)
                                <tr class="{{ $grade->remarks === 'failed' ? 'bg-red-50' : ($grade->remarks === 'inc' ? 'bg-yellow-50' : '') }}">
                                    <td class="px-4 py-2 font-bold">{{ $grade->subject->code }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-600">{{ $grade->subject->title }}</td>
                                    <td class="px-4 py-2 font-mono font-semibold">{{ $grade->grade ?? '—' }}</td>
                                    <td class="px-4 py-2">
                                        @if($grade->remarks === 'passed')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700">PASSED</span>
                                        @elseif($grade->remarks === 'failed')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">FAILED</span>
                                        @elseif($grade->remarks === 'inc')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-700">INC</span>
                                        @elseif($grade->remarks === 'dropped')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-600">DROPPED</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 py-4 text-center">No past grades found for this student.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Actions and Decision Panel --}}
        <div class="space-y-6">
            {{-- Approve Box --}}
            <div class="bg-white rounded-xl shadow p-5 border-t-4 border-green-500">
                <h3 class="font-bold text-gray-900 mb-2">1. Approve PEF</h3>
                <p class="text-xs text-gray-500 mb-4">Advances PEF to <strong class="text-indigo-600">Pending Admission</strong>.</p>
                <form method="POST" action="{{ route('adviser.pef.approve', $pef) }}">
                    @csrf
                    <textarea name="remarks" rows="2" placeholder="Optional approval remarks..." class="w-full border border-gray-300 rounded-lg text-sm p-2 mb-3 focus:ring-green-500 focus:border-green-500"></textarea>
                    <button type="submit" class="w-full py-2.5 px-4 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 shadow">
                        Approve & Forward to Admission
                    </button>
                </form>
            </div>

            {{-- Return Box --}}
            <div class="bg-white rounded-xl shadow p-5 border-t-4 border-amber-500">
                <h3 class="font-bold text-gray-900 mb-2">2. Return to Student</h3>
                <p class="text-xs text-gray-500 mb-4">Releases seats and returns PEF with mandatory remarks. Student edits and resubmits back to you.</p>
                <form method="POST" action="{{ route('adviser.pef.return', $pef) }}">
                    @csrf
                    <textarea name="remarks" rows="3" placeholder="Provide instructions on what needs to be changed (required, min 10 chars)..." class="w-full border border-gray-300 rounded-lg text-sm p-2 mb-3 focus:ring-amber-500 focus:border-amber-500" required></textarea>
                    <button type="submit" class="w-full py-2.5 px-4 bg-amber-600 text-white font-semibold text-sm rounded-lg hover:bg-amber-700 shadow">
                        Return with Remarks
                    </button>
                </form>
            </div>

            {{-- Reject Box --}}
            <div class="bg-white rounded-xl shadow p-5 border-t-4 border-red-500">
                <h3 class="font-bold text-gray-900 mb-2">3. Reject PEF</h3>
                <p class="text-xs text-gray-500 mb-4">Releases seats and permanently closes this PEF.</p>
                <form method="POST" action="{{ route('adviser.pef.reject', $pef) }}" onsubmit="return confirm('Permanently reject this PEF? This action cannot be undone.')">
                    @csrf
                    <textarea name="remarks" rows="2" placeholder="Reason for rejection (required)..." class="w-full border border-gray-300 rounded-lg text-sm p-2 mb-3 focus:ring-red-500 focus:border-red-500" required></textarea>
                    <button type="submit" class="w-full py-2.5 px-4 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 shadow">
                        Reject PEF Permanently
                    </button>
                </form>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 pb-3 border-b mb-3">Audit Trail Timeline</h3>
                <x-pef-timeline :approvals="$pef->approvals" />
            </div>
        </div>
    </div>
</div>
@endsection
