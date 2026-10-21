@extends('layouts.app')
@section('title', 'Adviser Queue')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Adviser Review Queue</h1>
            <p class="text-sm text-gray-500">PEFs waiting for your evaluation and prerequisite validation</p>
        </div>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 font-semibold rounded-full text-xs">Stage: Pending Advising</span>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student No.</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Program & Year</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Units</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pefs as $pef)
                <tr class="hover:bg-indigo-50/20">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $pef->student->user->name }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ $pef->student->student_no }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $pef->student->program->code }} - Yr {{ $pef->student->year_level }}</td>
                    <td class="px-4 py-3 font-bold text-indigo-700">{{ $pef->total_units }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $pef->updated_at->format('M d, Y h:i A') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('adviser.pef.show', $pef) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white font-medium text-xs rounded hover:bg-indigo-700 shadow">
                            Review PEF & History →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        No PEFs currently waiting for advising approval.
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
