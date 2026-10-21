<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\Room;
use App\Models\Section;
use App\Models\SectionSchedule;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $activeTerm  = Term::where('is_active', true)->first();
        $pastTerm    = Term::where('is_active', false)->first();
        $instructors = Instructor::all();
        $rooms       = Room::all();

        $sectionsData = [
            ['IT101', 'A', 30, 0, 0, [['Mon', '07:30', '09:00'], ['Wed', '07:30', '09:00']]],
            ['IT102', 'A', 30, 1, 1, [['Mon', '09:00', '10:30'], ['Wed', '09:00', '10:30']]],
            ['IT103', 'A', 30, 2, 2, [['Tue', '07:30', '09:00'], ['Thu', '07:30', '09:00']]],
            ['IT104', 'A', 30, 3, 3, [['Tue', '09:00', '10:30'], ['Thu', '09:00', '10:30']]],
            ['IT201', 'A', 30, 0, 0, [['Mon', '10:30', '12:00'], ['Wed', '10:30', '12:00']]],
            ['IT202', 'A', 30, 1, 1, [['Tue', '10:30', '12:00'], ['Thu', '10:30', '12:00']]],
            ['IT203', 'A', 30, 2, 4, [['Mon', '13:00', '14:30'], ['Wed', '13:00', '14:30']]],
            ['IT204', 'A', 30, 3, 5, [['Tue', '13:00', '14:30'], ['Thu', '13:00', '14:30']]],
            ['IT205', 'A', 30, 0, 2, [['Fri', '07:30', '10:30']]],
            ['IT301', 'A', 30, 1, 3, [['Fri', '10:30', '13:30']]],
            ['IT302', 'A', 30, 2, 0, [['Sat', '07:30', '10:30']]],
            ['IT303', 'A', 30, 3, 1, [['Sat', '10:30', '13:30']]],
        ];

        foreach ($sectionsData as [$subjectCode, $sectionCode, $capacity, $instrIdx, $roomIdx, $schedules]) {
            $subject    = Subject::where('code', $subjectCode)->first();
            $instructor = $instructors->get($instrIdx % $instructors->count());
            $room       = $rooms->get($roomIdx % $rooms->count());

            $section = Section::create([
                'subject_id'     => $subject->id,
                'term_id'        => $activeTerm->id,
                'section_code'   => $sectionCode,
                'capacity'       => $capacity,
                'enrolled_count' => 0,
                'instructor_id'  => $instructor?->id,
                'room_id'        => $room?->id,
            ]);

            foreach ($schedules as [$day, $start, $end]) {
                SectionSchedule::create([
                    'section_id' => $section->id,
                    'day'        => $day,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]);
            }
        }

        $pastSectionsData = [
            ['IT101', 'A', 30, 0, 0, [['Mon', '07:30', '09:00'], ['Wed', '07:30', '09:00']]],
            ['IT102', 'A', 30, 1, 1, [['Mon', '09:00', '10:30'], ['Wed', '09:00', '10:30']]],
            ['IT103', 'A', 30, 2, 2, [['Tue', '07:30', '09:00'], ['Thu', '07:30', '09:00']]],
            ['IT104', 'A', 30, 3, 3, [['Tue', '09:00', '10:30'], ['Thu', '09:00', '10:30']]],
            ['IT201', 'A', 30, 0, 0, [['Mon', '10:30', '12:00'], ['Wed', '10:30', '12:00']]],
            ['IT203', 'A', 30, 2, 4, [['Mon', '13:00', '14:30'], ['Wed', '13:00', '14:30']]],
            ['IT205', 'A', 30, 0, 2, [['Fri', '07:30', '10:30']]],
        ];

        foreach ($pastSectionsData as [$subjectCode, $sectionCode, $capacity, $instrIdx, $roomIdx, $schedules]) {
            $subject    = Subject::where('code', $subjectCode)->first();
            $instructor = $instructors->get($instrIdx % $instructors->count());
            $room       = $rooms->get($roomIdx % $rooms->count());

            $section = Section::create([
                'subject_id'     => $subject->id,
                'term_id'        => $pastTerm->id,
                'section_code'   => $sectionCode,
                'capacity'       => $capacity,
                'enrolled_count' => $capacity,
                'instructor_id'  => $instructor?->id,
                'room_id'        => $room?->id,
            ]);

            foreach ($schedules as [$day, $start, $end]) {
                SectionSchedule::create([
                    'section_id' => $section->id,
                    'day'        => $day,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]);
            }
        }
    }
}
