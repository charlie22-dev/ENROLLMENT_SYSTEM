<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'IT101', 'title' => 'Introduction to Computing',      'units' => 3, 'type' => 'lec'],
            ['code' => 'IT102', 'title' => 'Programming 1',                  'units' => 3, 'type' => 'lec'],
            ['code' => 'IT103', 'title' => 'Mathematics for IT',              'units' => 3, 'type' => 'lec'],
            ['code' => 'IT104', 'title' => 'Web Design Fundamentals',         'units' => 3, 'type' => 'lec'],
            ['code' => 'IT201', 'title' => 'Programming 2',                   'units' => 3, 'type' => 'lec'],
            ['code' => 'IT202', 'title' => 'Data Structures and Algorithms',  'units' => 3, 'type' => 'lec'],
            ['code' => 'IT203', 'title' => 'Database Systems',                'units' => 3, 'type' => 'lec'],
            ['code' => 'IT204', 'title' => 'Web Development 1',               'units' => 3, 'type' => 'lec'],
            ['code' => 'IT205', 'title' => 'Computer Networks',               'units' => 3, 'type' => 'lec'],
            ['code' => 'IT301', 'title' => 'Software Engineering',            'units' => 3, 'type' => 'lec'],
            ['code' => 'IT302', 'title' => 'Human Computer Interaction',      'units' => 3, 'type' => 'lec'],
            ['code' => 'IT303', 'title' => 'Information Security',            'units' => 3, 'type' => 'lec'],
        ];

        foreach ($subjects as $subject) {
            Subject::create(array_merge($subject, ['created_at' => now(), 'updated_at' => now()]));
        }

        $prereqs = [
            'IT201' => ['IT102'],
            'IT202' => ['IT201'],
            'IT203' => ['IT101'],
            'IT204' => ['IT102', 'IT203'],
            'IT205' => ['IT101'],
            'IT301' => ['IT202', 'IT204'],
            'IT302' => ['IT201'],
            'IT303' => ['IT205'],
        ];

        foreach ($prereqs as $subjectCode => $prereqCodes) {
            $subject = Subject::where('code', $subjectCode)->first();
            $prereqIds = Subject::whereIn('code', $prereqCodes)->pluck('id');
            $subject->prerequisites()->sync($prereqIds);
        }
    }
}
