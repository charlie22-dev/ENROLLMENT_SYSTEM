@extends('layouts.app')
@section('title', 'Process Cashier Payment')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cashier Assessment & Payment: {{ $pef->student->user->name }}</h1>
            <p class="text-sm text-gray-500">Student No: <strong>{{ $pef->student->student_no }}</strong> | Program: <strong>{{ $pef->student->program->name }}</strong></p>
        </div>
        <x-status-badge :status="$pef->status" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Student Summary & Assessment Breakdown --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 pb-2 border-b mb-3">Student & PEF Information</h3>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-xs text-gray-500">Student Name</dt><dd class="font-bold">{{ $pef->student->user->name }}</dd></div>
                    <div><dt class="text-xs text-gray-500">Student No.</dt><dd class="font-mono font-semibold">{{ $pef->student->student_no }}</dd></div>
                    <div><dt class="text-xs text-gray-500">Term</dt><dd class="font-semibold">{{ $pef->term->label() }}</dd></div>
                    <div><dt class="text-xs text-gray-500">Total Enrolled Units</dt><dd class="font-bold text-indigo-700 text-lg">{{ $pef->total_units }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-600">
                <h3 class="font-bold text-gray-800 pb-2 border-b mb-3">Itemized Assessment</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Tuition Fee ({{ $pef->total_units }} units × ₱{{ number_format(config('fees.tuition_per_unit'), 2) }}):</span>
                        <span class="font-bold">₱{{ number_format($assessment->tuition, 2) }}</span>
                    </div>
                    @foreach($assessment->misc_fees as $fee)
                    <div class="flex justify-between py-1 border-t border-gray-100">
                        <span class="text-gray-600">{{ $fee['name'] }}:</span>
                        <span class="font-medium">₱{{ number_format($fee['amount'], 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-3 border-t-2 border-gray-300 text-lg font-extrabold text-indigo-900 bg-indigo-50/50 px-2 rounded">
                        <span>Total Due:</span>
                        <span>₱{{ number_format($assessment->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 pb-2 border-b mb-3">Audit Trail</h3>
                <x-pef-timeline :approvals="$pef->approvals" />
            </div>
        </div>

        {{-- Payment Recording Form --}}
        <div>
            <div class="bg-white rounded-xl shadow p-6 border-t-4 border-green-600 sticky top-20">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Record Manual Payment</h2>
                <p class="text-xs text-gray-500 mb-6">Enter the official receipt (OR) number and payment amount received over the counter. Recording payment officially marks the student as <strong class="text-green-600">ENROLLED</strong>.</p>

                <form method="POST" action="{{ route('cashier.pef.payment', $pef) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Official Receipt (OR) Number <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="or_number"
                               value="{{ old('or_number', 'OR-' . date('Y') . '-' . str_pad($pef->id, 5, '0', STR_PAD_LEFT)) }}"
                               placeholder="e.g. OR-2024-00123"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500 font-mono font-bold"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Payment Amount Received (₱) <span class="text-red-500">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="amount"
                               value="{{ old('amount', $assessment->total) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500 font-mono text-base font-bold text-green-800"
                               required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-4 bg-green-600 text-white font-bold text-base rounded-lg shadow hover:bg-green-700 transition" onclick="return confirm('Confirm receipt of payment and finalize enrollment?')">
                            Issue Receipt & Officially Enroll Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
