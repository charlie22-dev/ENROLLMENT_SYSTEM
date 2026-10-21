@extends('layouts.app')
@section('title', 'School Terms')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Academic Terms & Enrollment Periods</h1>
            <p class="text-sm text-gray-500">Configure semesters and set active enrollment terms</p>
        </div>
        <a href="{{ route('admin.terms.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Term
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">School Year</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Semester</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Enrollment Window</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Active Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($terms as $term)
                <tr class="{{ $term->is_active ? 'bg-green-50/40' : 'hover:bg-gray-50' }}">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $term->school_year }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $term->semester }} Semester</td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">
                        {{ $term->enrollment_start->format('M d, Y') }} — {{ $term->enrollment_end->format('M d, Y') }}
                        @if($term->isEnrollmentOpen())
                            <span class="ml-2 text-xs font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">OPEN NOW</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($term->is_active)
                            <span class="px-2.5 py-1 text-xs font-bold bg-green-600 text-white rounded-full">ACTIVE TERM</span>
                        @else
                            <form method="POST" action="{{ route('admin.terms.activate', $term) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-900 border border-indigo-200 px-2 py-0.5 rounded hover:bg-indigo-50" onclick="return confirm('Set this as the single active term?')">
                                    Set as Active
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.terms.edit', $term) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        @if(!$term->is_active)
                        <form method="POST" action="{{ route('admin.terms.destroy', $term) }}" class="inline" onsubmit="return confirm('Delete this term?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No terms found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $terms->links() }}
</div>
@endsection
