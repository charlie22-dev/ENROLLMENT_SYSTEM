<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Room 101',  'building' => 'Main Building', 'capacity' => 40],
            ['name' => 'Room 102',  'building' => 'Main Building', 'capacity' => 40],
            ['name' => 'Room 201',  'building' => 'Main Building', 'capacity' => 35],
            ['name' => 'Room 202',  'building' => 'Main Building', 'capacity' => 35],
            ['name' => 'Lab 301',   'building' => 'Tech Building', 'capacity' => 30],
            ['name' => 'Lab 302',   'building' => 'Tech Building', 'capacity' => 30],
        ];

        foreach ($rooms as $room) {
            Room::create(array_merge($room, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
