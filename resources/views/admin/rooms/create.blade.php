@extends('layouts.app')
@section('title', 'Add Room')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add Room</h1>
        <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Room Name / Number</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Room 101 or Lab 301" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Building</label>
                <input type="text" name="building" value="{{ old('building') }}" placeholder="e.g. Main Building" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seating Capacity</label>
                <input type="number" name="capacity" value="{{ old('capacity', 40) }}" min="1" max="500" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 shadow">Save Room</button>
            </div>
        </form>
    </div>
</div>
@endsection
