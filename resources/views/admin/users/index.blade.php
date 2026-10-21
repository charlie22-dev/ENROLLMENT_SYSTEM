@extends('layouts.app')
@section('title', 'Users & Accounts')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Accounts & Roles</h1>
            <p class="text-sm text-gray-500">Manage students, advisers, admission officers, registrars, and cashiers</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student Info</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $user->role === 'student' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $user->role === 'adviser' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $user->role === 'admission' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $user->role === 'registrar' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $user->role === 'cashier' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        ">{{ $user->role }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">
                        @if($user->student)
                            <span class="font-mono font-bold">{{ $user->student->student_no }}</span>
                            <span>({{ $user->student->program?->code ?? 'N/A' }} - Yr {{ $user->student->year_level }})</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
