@extends('layouts.app')
@section('title', 'Edit Department')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Department: {{ $department->code }}</h1>
        <a href="{{ route('admin.departments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.departments.update', $department) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Department Code</label>
                <input type="text" name="code" value="{{ old('code', $department->code) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Department / College Name</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.departments.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Update Department</button>
            </div>
        </form>
    </div>
</div>
@endsection
