@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-600">
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-1">Student No: <span class="font-semibold text-gray-700">{{ $student?->student_no ?? 'N/A' }}</span> | Program: <span class="font-semibold text-gray-700">{{ $student?->program?->code ?? 'N/A' }}</span> | Year Level: <span class="font-semibold text-gray-700">{{ $student?->year_level ?? '1' }}</span></p>
    </div>

    @if($activePef)
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Pre-Enrollment Form (PEF)</h2>
                    <p class="text-xs text-gray-500">Term: {{ $activePef->term->label() }}</p>
                </div>
                <x-status-badge :status="$activePef->status" />
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                <div>
                    <span class="text-xs text-gray-500 block">Total Units</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $activePef->total_units }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Subjects Picked</span>
                    <span class="text-lg font-bold text-gray-800">{{ $activePef->items->count() }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Adviser</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $student?->adviser?->name ?? 'Unassigned' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Last Updated</span>
                    <span class="text-sm text-gray-600">{{ $activePef->updated_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            @if($activePef->status === 'returned' && $activePef->remarks)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded mb-6">
                    <p class="text-sm font-bold text-amber-800">Returned With Remarks:</p>
                    <p class="text-sm text-amber-900 mt-1 italic">"{{ $activePef->remarks }}"</p>
                    <p class="text-xs text-amber-700 mt-2 font-medium">Please edit your selected sections and resubmit your PEF. Resubmissions restart at Advising.</p>
                </div>
            @endif

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('student.pef.show', $activePef) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    View Full PEF
                </a>
                @if($activePef->isEditable())
                    <a href="{{ route('student.pef.edit', $activePef) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700">
                        Edit Sections & Resubmit
                    </a>
                @endif
                @if($activePef->status === 'enrolled')
                    <a href="{{ route('student.pef.cor', $activePef) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                        View Certificate of Registration (COR)
                    </a>
                @endif
            </div>
        </div>
    @elseif($activeTerm && $activeTerm->isEnrollmentOpen())
        <div class="bg-white rounded-xl shadow p-8 text-center border-dashed border-2 border-indigo-200">
            <svg class="w-12 h-12 text-indigo-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h2 class="text-xl font-bold text-gray-800 mb-2">No Active Pre-Enrollment Form</h2>
            <p class="text-gray-500 mb-6">Enrollment is officially open for <strong class="text-indigo-600">{{ $activeTerm->label() }}</strong></p>
            <a href="{{ route('student.pef.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700">
                Start Pre-Enrollment (Fill out PEF)
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">
            <p>Enrollment is currently closed for the term.</p>
        </div>
    @endif
</div>
@endsection
