@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Administration</h1>
            <p class="text-sm text-gray-500">Academic catalog, terms, sections, and user management</p>
        </div>
        @if($stats['active_term'])
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                Active Term: {{ $stats['active_term']->label() }}
            </span>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-indigo-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">Students</span>
            <p class="text-2xl font-extrabold text-indigo-700 mt-1">{{ $stats['total_students'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-blue-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Users</span>
            <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-green-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">Enrolled</span>
            <p class="text-2xl font-extrabold text-green-700 mt-1">{{ $stats['enrolled_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-amber-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">In Progress</span>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $stats['pending_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-purple-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">PEFs Today</span>
            <p class="text-2xl font-extrabold text-purple-700 mt-1">{{ $stats['pefs_today'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-t-4 border-gray-600">
            <span class="text-xs font-semibold text-gray-500 uppercase">Active Status</span>
            <p class="text-sm font-bold text-gray-800 mt-2">{{ $stats['active_term'] ? 'Open' : 'No Active Term' }}</p>
        </div>
    </div>

    {{-- Management Links --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold text-gray-800 text-lg mb-4 pb-2 border-b">Catalog & Master Data Management</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('admin.departments.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Departments</span>
                <span class="text-xs text-gray-400">Colleges & Depts</span>
            </a>
            <a href="{{ route('admin.programs.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Degree Programs</span>
                <span class="text-xs text-gray-400">BSIT, BSCS, etc.</span>
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Subjects</span>
                <span class="text-xs text-gray-400">Units & Prerequisites</span>
            </a>
            <a href="{{ route('admin.curriculum.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Curriculum</span>
                <span class="text-xs text-gray-400">Program Flow</span>
            </a>
            <a href="{{ route('admin.terms.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">School Terms</span>
                <span class="text-xs text-gray-400">Semesters & Periods</span>
            </a>
            <a href="{{ route('admin.sections.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Sections</span>
                <span class="text-xs text-gray-400">Schedules & Slots</span>
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Rooms</span>
                <span class="text-xs text-gray-400">Classrooms & Labs</span>
            </a>
            <a href="{{ route('admin.instructors.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Instructors</span>
                <span class="text-xs text-gray-400">Faculty Members</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border rounded-lg text-center transition group">
                <span class="font-bold text-gray-800 group-hover:text-indigo-600 block">Users & Roles</span>
                <span class="text-xs text-gray-400">Accounts & Offices</span>
            </a>
        </div>
    </div>
</div>
@endsection
