@extends('layouts.app')
@section('title', 'Admission Review')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admission Review: {{ $pef->student->user->name }}</h1>
            <p class="text-sm text-gray-500">Student No: <strong>{{ $pef->student->student_no }}</strong> | Program: <strong>{{ $pef->student->program->name }}</strong></p>
        </div>
        <x-status-badge :status="$pef->status" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-xs text-gray-500 block">Term</span><span class="font-semibold">{{ $pef->term->label() }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Total Units</span><span class="font-bold text-purple-700 text-lg">{{ $pef->total_units }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Adviser</span><span class="font-semibold">{{ $pef->student->adviser?->name ?? 'None' }}</span></div>
                    <div><span class="text-xs text-gray-500 block">Status</span><x-status-badge :status="$pef->status" /></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50"><h2 class="font-bold text-gray-800">Sections To Be Approved</h2></div>
                <table class="min-w-full text-sm divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs text-gray-500 uppercase">Subject</th>
                            <th class="px-4 py-2.5 text-left text-xs text-gray-500 uppercase">Sec</th>
                            <th class="px-4 py-2.5 text-left text-xs text-gray-500 uppercase">Units</th>
                            <th class="px-4 py-2.5 text-left text-xs text-gray-500 uppercase">Schedule</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pef->items as $item)
                        <tr>
                            <td class="px-4 py-3"><div class="font-bold">{{ $item->section->subject->code }}</div><div class="text-xs text-gray-500">{{ $item->section->subject->title }}</div></td>
                            <td class="px-4 py-3 font-semibold">{{ $item->section->section_code }}</td>
                            <td class="px-4 py-3">{{ $item->section->subject->units }}</td>
                            <td class="px-4 py-3 text-xs">@foreach($item->section->schedules as $s)<div class="font-mono">{{ $s->day }} {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}</div>@endforeach</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-5 border-t-4 border-green-500">
                <h3 class="font-bold text-gray-900 mb-2">Approve Records</h3>
                <p class="text-xs text-gray-500 mb-4">Advances to <strong class="text-indigo-600">Pending Registrar</strong>.</p>
                <form method="POST" action="{{ route('admission.pef.approve', $pef) }}">
                    @csrf
                    <textarea name="remarks" rows="2" placeholder="Optional approval remarks..." class="w-full border border-gray-300 rounded-lg text-sm p-2 mb-3"></textarea>
                    <button type="submit" class="w-full py-2.5 px-4 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 shadow">
                        Approve & Forward to Registrar
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-t-4 border-amber-500">
                <h3 class="font-bold text-gray-900 mb-2">Return with Remarks</h3>
                <p class="text-xs text-gray-500 mb-4">Returns to student with remarks. Resubmission restarts at Advising.</p>
                <form method="POST" action="{{ route('admission.pef.return', $pef) }}">
                    @csrf
                    <textarea name="remarks" rows="3" placeholder="State reason / missing requirements (required)..." class="w-full border border-gray-300 rounded-lg text-sm p-2 mb-3" required></textarea>
                    <button type="submit" class="w-full py-2.5 px-4 bg-amber-600 text-white font-semibold text-sm rounded-lg hover:bg-amber-700 shadow">
                        Return to Student
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 pb-3 border-b mb-3">Audit Timeline</h3>
                <x-pef-timeline :approvals="$pef->approvals" />
            </div>
        </div>
    </div>
</div>
@endsection
