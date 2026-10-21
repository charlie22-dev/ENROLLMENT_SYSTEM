@extends('layouts.app')
@section('title', 'Rooms')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Classrooms & Labs</h1>
            <p class="text-sm text-gray-500">Facility rooms and seat capacities</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">
            + New Room
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Room Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Building</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Capacity</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rooms as $room)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold text-gray-900">{{ $room->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $room->building ?? 'N/A' }}</td>
                    <td class="px-4 py-3 font-semibold text-indigo-700">{{ $room->capacity }} seats</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" class="inline" onsubmit="return confirm('Delete this room?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No rooms found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $rooms->links() }}
</div>
@endsection
