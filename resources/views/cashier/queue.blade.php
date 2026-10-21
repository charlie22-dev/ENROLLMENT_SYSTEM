@extends('layouts.app')
@section('title', 'Cashier Queue')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cashier Payment Queue</h1>
            <p class="text-sm text-gray-500">Collect manual assessment payments and officially enroll students</p>
        </div>
        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 font-semibold rounded-full text-xs">Stage: Pending Cashier</span>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student No.</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Program</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Est. Assessment</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pefs as $pef)
                @php
                    $miscTotal = collect(config('fees.misc', []))->sum('amount');
                    $estTotal = ($pef->total_units * config('fees.tuition_per_unit', 450)) + $miscTotal;
                @endphp
                <tr class="hover:bg-yellow-50/20">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $pef->student->user->name }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ $pef->student->student_no }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $pef->student->program->code }} - Yr {{ $pef->student->year_level }}</td>
                    <td class="px-4 py-3">{{ $pef->total_units }}</td>
                    <td class="px-4 py-3 font-bold text-green-700">₱{{ number_format($estTotal, 2) }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('cashier.pef.show', $pef) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white font-medium text-xs rounded hover:bg-yellow-700 shadow">
                            Process Payment & Enroll →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        No PEFs currently waiting for cashier payment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $pefs->links() }}
    </div>
</div>
@endsection
