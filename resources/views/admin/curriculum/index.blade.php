@extends('layouts.app')
@section('title', 'Curriculum Mapping')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Curriculum Mapping</h1>
            <p class="text-sm text-gray-500">Subjects assigned to degree programs by year level and semester</p>
        </div>
        <a href="{{ route('admin.curriculum.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + Add to Curriculum
        </a>
    </div>

    @foreach($programs as $prog)
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-5 py-4 bg-gray-50 border-b flex justify-between items-center">
            <h2 class="font-bold text-gray-900 text-base">{{ $prog->name }} ({{ $prog->code }})</h2>
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold">{{ $prog->curriculumSubjects->count() }} Subjects</span>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Year</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Term</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($prog->curriculumSubjects->sortBy(['year_level', 'term_no']) as $cs)
                <tr>
                    <td class="px-4 py-2.5 font-semibold text-gray-700">Year {{ $cs->year_level }}</td>
                    <td class="px-4 py-2.5 font-semibold text-gray-600">{{ $cs->term_no == 3 ? 'Summer' : $cs->term_no . ' Sem' }}</td>
                    <td class="px-4 py-2.5 font-mono font-bold text-indigo-700">{{ $cs->subject->code }}</td>
                    <td class="px-4 py-2.5 text-gray-800">{{ $cs->subject->title }}</td>
                    <td class="px-4 py-2.5">{{ $cs->subject->units }}</td>
                    <td class="px-4 py-2.5 text-right space-x-2">
                        <a href="{{ route('admin.curriculum.edit', $cs) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.curriculum.destroy', $cs) }}" class="inline" onsubmit="return confirm('Remove subject from curriculum?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No curriculum assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach
</div>
@endsection
