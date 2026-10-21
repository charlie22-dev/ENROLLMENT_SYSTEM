@extends('layouts.app')
@section('title', 'Subjects')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Subjects Catalog</h1>
            <p class="text-sm text-gray-500">Master list of subjects, units, and prerequisites</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Subject
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Units</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prerequisites</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subjects as $subj)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold text-indigo-700">{{ $subj->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $subj->title }}</td>
                    <td class="px-4 py-3">{{ $subj->units }}</td>
                    <td class="px-4 py-3 uppercase text-xs font-semibold text-gray-600">{{ $subj->type }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.subjects.prerequisites.index', $subj) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:underline">
                            {{ $subj->prerequisites_count }} prereq(s) →
                        </a>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.subjects.edit', $subj) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.subjects.destroy', $subj) }}" class="inline" onsubmit="return confirm('Delete this subject?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No subjects found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $subjects->links() }}
</div>
@endsection
