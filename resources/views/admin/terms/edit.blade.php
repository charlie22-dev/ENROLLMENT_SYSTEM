@extends('layouts.app')
@section('title', 'Edit Term')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Academic Term</h1>
        <a href="{{ route('admin.terms.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.terms.update', $term) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">School Year</label>
                <input type="text" name="school_year" value="{{ old('school_year', $term->school_year) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                <select name="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="1st" @selected(old('semester', $term->semester) === '1st')>1st Semester</option>
                    <option value="2nd" @selected(old('semester', $term->semester) === '2nd')>2nd Semester</option>
                    <option value="summer" @selected(old('semester', $term->semester) === 'summer')>Summer</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Enrollment Start Date</label>
                    <input type="date" name="enrollment_start" value="{{ old('enrollment_start', $term->enrollment_start->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Enrollment End Date</label>
                    <input type="date" name="enrollment_end" value="{{ old('enrollment_end', $term->enrollment_end->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active', $term->is_active))>
                <label for="is_active" class="text-sm font-medium text-gray-700">Set as Active Enrollment Term</label>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.terms.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Update Term</button>
            </div>
        </form>
    </div>
</div>
@endsection
