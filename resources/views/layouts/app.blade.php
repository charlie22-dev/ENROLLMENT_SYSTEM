<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Enrollment System') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="font-bold text-indigo-700 text-lg flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        {{ config('app.name') }}
                    </a>
                    @auth
                        @if(auth()->user()->isStudent())
                            <a href="{{ route('student.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Dashboard</a>
                            <a href="{{ route('student.board') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Course Board</a>
                        @elseif(auth()->user()->isAdviser())
                            <a href="{{ route('adviser.queue') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">PEF Queue</a>
                        @elseif(auth()->user()->isAdmission())
                            <a href="{{ route('admission.queue') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">PEF Queue</a>
                        @elseif(auth()->user()->isRegistrar())
                            <a href="{{ route('registrar.queue') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">PEF Queue</a>
                        @elseif(auth()->user()->isCashier())
                            <a href="{{ route('cashier.queue') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Payment Queue</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Dashboard</a>
                            <a href="{{ route('admin.departments.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Departments</a>
                            <a href="{{ route('admin.programs.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Programs</a>
                            <a href="{{ route('admin.subjects.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Subjects</a>
                            <a href="{{ route('admin.curriculum.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Curriculum</a>
                            <a href="{{ route('admin.terms.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Terms</a>
                            <a href="{{ route('admin.sections.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Sections</a>
                            <a href="{{ route('admin.rooms.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Rooms</a>
                            <a href="{{ route('admin.instructors.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Instructors</a>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Users</a>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        <div class="relative">
                            <span class="inline-flex items-center justify-center p-1 text-gray-500 hover:text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </span>
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center font-bold">{{ $unread }}</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</div>
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium uppercase tracking-wider">{{ auth()->user()->role }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-lg">
                <div class="font-semibold mb-1">Please fix the following issues:</div>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
