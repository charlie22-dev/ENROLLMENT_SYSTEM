@extends('layouts.app')
@section('title', 'Add Program')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add Academic Program</h1>
        <a href="{{ route('admin.programs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                <select name="department_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }} ({{ $dept->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Program Code</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. BSIT" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Program Title</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Bachelor of Science in Information Technology" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.programs.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Save Program</button>
            </div>
        </form>
    </div>
</div>
@endsection
