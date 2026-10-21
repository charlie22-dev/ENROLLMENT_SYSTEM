@extends('layouts.app')
@section('title', 'Add User')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add New User Account</h1>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4" id="user-form">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. jdoe@school.edu" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="At least 8 characters" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Assigned Role</label>
                <select name="role" id="role-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="student" @selected(old('role') === 'student')>Student</option>
                    <option value="adviser" @selected(old('role') === 'adviser')>Adviser</option>
                    <option value="admission" @selected(old('role') === 'admission')>Admission Officer</option>
                    <option value="registrar" @selected(old('role') === 'registrar')>Registrar</option>
                    <option value="cashier" @selected(old('role') === 'cashier')>Cashier</option>
                    <option value="admin" @selected(old('role') === 'admin')>Administrator</option>
                </select>
            </div>

            {{-- Student extra fields --}}
            <div id="student-fields" class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-4">
                <h3 class="font-bold text-gray-800 text-sm">Student Profile Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Student No.</label>
                        <input type="text" name="student_no" value="{{ old('student_no') }}" placeholder="e.g. 2024-0010" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Year Level</label>
                        <select name="year_level" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach([1, 2, 3, 4, 5] as $y)
                                <option value="{{ $y }}" @selected(old('year_level') == $y)>Year {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Degree Program</label>
                        <select name="program_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Program</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}" @selected(old('program_id') == $prog->id)>{{ $prog->name }} ({{ $prog->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Assigned Academic Adviser</label>
                        <select name="adviser_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Unassigned</option>
                            @foreach($advisers as $adv)
                                <option value="{{ $adv->id }}" @selected(old('adviser_id') == $adv->id)>{{ $adv->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Save User Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('role-select');
    const studentFields = document.getElementById('student-fields');

    function toggleStudentFields() {
        if (roleSelect.value === 'student') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    }
    roleSelect.addEventListener('change', toggleStudentFields);
    toggleStudentFields();
</script>
@endsection
