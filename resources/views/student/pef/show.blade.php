@extends('layouts.app')
@section('title', 'Pre-Enrollment Form Details')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pre-Enrollment Form (PEF #{{ $pef->id }})</h1>
            <p class="text-sm text-gray-500">Term: <strong class="text-indigo-600">{{ $pef->term->label() }}</strong></p>
        </div>
        <div>
            <x-status-badge :status="$pef->status" />
        </div>
    </div>

    @if($pef->status === 'returned' && $pef->remarks)
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded shadow-sm">
            <h3 class="font-bold text-amber-800 text-sm">PEF Returned with Remarks:</h3>
            <p class="text-amber-900 text-sm mt-1 italic">"{{ $pef->remarks }}"</p>
            <p class="text-xs text-amber-700 mt-2 font-medium">Please edit your sections and submit again. Resubmitting will reset status to Pending Advising.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Student overview card --}}
            <div class="bg-white rounded-xl shadow p-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 text-xs block">Student Name</span>
                        <span class="font-semibold text-gray-800">{{ $pef->student->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Student No.</span>
                        <span class="font-semibold text-gray-800">{{ $pef->student->student_no }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Total Units</span>
                        <span class="font-bold text-indigo-600 text-base">{{ $pef->total_units }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Status</span>
                        <x-status-badge :status="$pef->status" />
                    </div>
                </div>
            </div>

            {{-- Chosen Sections --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Enrolled / Picked Sections</h2>
                    <span class="text-xs text-gray-500">{{ $pef->items->count() }} subjects</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sec</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Schedule</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Room</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Instructor</th>
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
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $item->section->room?->name ?? 'TBA' }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $item->section->instructor?->name ?? 'TBA' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Assessment if created --}}
            @if($pef->assessment)
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Tuition & Fee Assessment</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Tuition Fee ({{ $pef->total_units }} units × ₱{{ number_format(config('fees.tuition_per_unit'), 2) }}):</span>
                        <span class="font-semibold">₱{{ number_format($pef->assessment->tuition, 2) }}</span>
                    </div>
                    @foreach($pef->assessment->misc_fees as $fee)
                    <div class="flex justify-between py-1 border-t border-gray-100">
                        <span class="text-gray-600">{{ $fee['name'] }}:</span>
                        <span class="font-semibold">₱{{ number_format($fee['amount'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-3 border-t-2 border-gray-200 text-base font-bold">
                        <span class="text-gray-900">Total Assessment:</span>
                        <span class="text-indigo-700">₱{{ number_format($pef->assessment->total, 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment info if enrolled --}}
            @if($pef->payments->isNotEmpty())
            <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                <h2 class="font-bold text-green-900 mb-2">Payment Recorded</h2>
                @foreach($pef->payments as $payment)
                <div class="text-sm text-green-800 space-y-1">
                    <p><strong>OR Number:</strong> {{ $payment->or_number }}</p>
                    <p><strong>Amount Paid:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Received by:</strong> {{ $payment->receiver?->name ?? 'Cashier' }} on {{ $payment->paid_at->format('M d, Y h:i A') }}</p>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Student Action Buttons --}}
            <div class="flex flex-wrap gap-3 pt-2">
                @if($pef->isEditable())
                    <a href="{{ route('student.pef.edit', $pef) }}" class="px-5 py-2.5 bg-gray-700 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 shadow">
                        Edit Sections
                    </a>
                    <form method="POST" action="{{ route('student.pef.submit', $pef) }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow" onclick="return confirm('Submit this PEF for Advising? This will reserve your seats.')">
                            Submit PEF for Advising
                        </button>
                    </form>
                @endif

                @if($pef->status === 'enrolled')
                    <a href="{{ route('student.pef.cor', $pef) }}" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow">
                        View Official COR
                    </a>
                    <a href="{{ route('student.pef.cor.pdf', $pef) }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 shadow">
                        Download PDF
                    </a>
                @endif

                @if($pef->isActive() && !$pef->isEditable())
                    <form method="POST" action="{{ route('student.pef.cancel', $pef) }}" onsubmit="return confirm('Are you sure you want to cancel this PEF? This will release reserved seats.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                            Cancel PEF
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Approvals Timeline --}}
        <div>
            <div class="bg-white rounded-xl shadow p-5 sticky top-20">
                <h2 class="font-bold text-gray-800 pb-3 border-b mb-4">Enrollment Process Timeline</h2>
                @if($pef->approvals->isEmpty())
                    <p class="text-sm text-gray-400 py-4 text-center">No audit trail entries yet.</p>
                @else
                    <x-pef-timeline :approvals="$pef->approvals" />
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
