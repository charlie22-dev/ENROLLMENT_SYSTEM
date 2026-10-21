@extends('layouts.app')
@section('title', 'Manage Prerequisites — ' . $subject->code)
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Prerequisites for {{ $subject->code }}</h1>
            <p class="text-sm text-gray-500">{{ $subject->title }} ({{ $subject->units }} units)</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Subjects</a>
    </div>

    {{-- Add Prerequisite Form --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-bold text-gray-800 text-sm mb-3">Add Prerequisite Subject</h2>
        <form method="POST" action="{{ route('admin.subjects.prerequisites.store', $subject) }}" class="flex gap-3">
            @csrf
            <select name="prerequisite_id" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">Select subject that must be passed first...</option>
                @foreach($allSubjects as $s)
                    <option value="{{ $s->id }}">{{ $s->code }} — {{ $s->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
                Attach Prerequisite
            </button>
        </form>
    </div>

    {{-- Current Prerequisites Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-800 text-sm">
            Current Required Prerequisites
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Remove</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subject->prerequisites as $prereq)
                <tr>
                    <td class="px-4 py-3 font-mono font-bold text-indigo-700">{{ $prereq->code }}</td>
                    <td class="px-4 py-3 text-gray-900">{{ $prereq->title }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $prereq->units }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.subjects.prerequisites.destroy', [$subject, $prereq]) }}" onsubmit="return confirm('Remove this prerequisite?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No prerequisites set for this subject.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
