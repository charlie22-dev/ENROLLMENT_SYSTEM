<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::paginate(20);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:50|unique:rooms',
            'building' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1',
        ]);
        Room::create($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Room created.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:50|unique:rooms,name,' . $room->id,
            'building' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1',
        ]);
        $room->update($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted.');
    }
}
