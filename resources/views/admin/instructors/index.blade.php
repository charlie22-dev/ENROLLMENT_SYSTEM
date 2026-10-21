@extends('layouts.app')
@section('title', 'Instructors')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Faculty & Instructors</h1>
            <p class="text-sm text-gray-500">Instructor roster assigned to course sections</p>
        </div>
        <a href="{{ route('admin.instructors.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Instructor
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Instructor Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($instructors as $inst)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $inst->name }}</td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $inst->email ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $inst->department?->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.instructors.edit', $inst) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.instructors.destroy', $inst) }}" class="inline" onsubmit="return confirm('Delete this instructor?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No instructors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $instructors->links() }}
</div>
@endsection
