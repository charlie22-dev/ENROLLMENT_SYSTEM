@extends('layouts.app')
@section('title', 'Degree Programs')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Academic Programs</h1>
            <p class="text-sm text-gray-500">Degree programs offered</p>
        </div>
        <a href="{{ route('admin.programs.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Program
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Program Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($programs as $program)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold text-indigo-700">{{ $program->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $program->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $program->department->name }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.programs.edit', $program) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" class="inline" onsubmit="return confirm('Delete this program?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No programs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $programs->links() }}
</div>
@endsection
