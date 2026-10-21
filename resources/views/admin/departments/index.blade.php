@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Departments / Colleges</h1>
            <p class="text-sm text-gray-500">Manage academic departments</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Department
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Programs</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($departments as $dept)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold text-indigo-700">{{ $dept->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $dept->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $dept->programs_count }} programs</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.departments.edit', $dept) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" class="inline" onsubmit="return confirm('Delete this department?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No departments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $departments->links() }}
</div>
@endsection
