@extends('layouts.app')
@section('title', 'Add to Curriculum')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add Subject to Curriculum</h1>
        <a href="{{ route('admin.curriculum.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.curriculum.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Degree Program</label>
                <select name="program_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Select Program</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" @selected(old('program_id') == $p->id)>{{ $p->name }} ({{ $p->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Subject</label>
                <select name="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" @selected(old('subject_id') == $s->id)>{{ $s->code }} — {{ $s->title }} ({{ $s->units }} units)</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Year Level</label>
                    <select name="year_level" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        @foreach([1, 2, 3, 4, 5] as $y)
                            <option value="{{ $y }}" @selected(old('year_level') == $y)>Year {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Semester / Term</label>
                    <select name="term_no" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="1" @selected(old('term_no') == 1)>1st Semester</option>
                        <option value="2" @selected(old('term_no') == 2)>2nd Semester</option>
                        <option value="3" @selected(old('term_no') == 3)>Summer</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.curriculum.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Add to Curriculum</button>
            </div>
        </form>
    </div>
</div>
@endsection
