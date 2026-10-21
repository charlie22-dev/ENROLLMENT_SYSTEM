<?php

namespace App\Services;

use App\Exceptions\EnrollmentValidationException;
use App\Models\Section;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class PefValidator
{
    private MessageBag $errors;

    public function __construct()
    {
        $this->errors = new MessageBag();
    }

    public function validate(Student $student, Term $term, array $sectionIds): void
    {
        $this->errors = new MessageBag();

        $sections = Section::with(['subject.prerequisites', 'schedules'])
            ->whereIn('id', $sectionIds)
            ->get();

        $this->checkEnrollmentPeriod($term);
        $this->checkUnitLoad($sections);
        $this->checkPrerequisites($student, $sections);
        $this->checkScheduleConflicts($sections);
        $this->checkCapacity($sectionIds);

        if ($this->errors->isNotEmpty()) {
            throw new EnrollmentValidationException($this->errors);
        }
    }

    public function checkEnrollmentPeriod(Term $term): void
    {
        if (! $term->isEnrollmentOpen()) {
            $this->errors->add(
                'enrollment_period',
                'The enrollment period for the active term is not open.'
            );
        }
    }

    public function checkUnitLoad($sections): void
    {
        $totalUnits = $sections->sum(fn($s) => $s->subject->units ?? 0);
        $min = config('fees.min_units', 12);
        $max = config('fees.max_units', 25);

        if ($totalUnits < $min) {
            $this->errors->add('units', "Minimum unit load is {$min}. You selected {$totalUnits} units.");
        }

        if ($totalUnits > $max) {
            $this->errors->add('units', "Maximum unit load is {$max}. You selected {$totalUnits} units.");
        }
    }

    public function checkPrerequisites(Student $student, $sections): void
    {
        $passedSubjectIds = $student->grades()
            ->where('remarks', 'passed')
            ->pluck('subject_id')
            ->toArray();

        foreach ($sections as $section) {
            $subject = $section->subject;
            if (!$subject) continue;
            foreach ($subject->prerequisites as $prereq) {
                if (! in_array($prereq->id, $passedSubjectIds, true)) {
                    $this->errors->add(
                        'prerequisites',
                        "You have not passed the prerequisite '{$prereq->code} {$prereq->title}' for '{$subject->code} {$subject->title}'."
                    );
                }
            }
        }
    }

    public function checkScheduleConflicts($sections): void
    {
        $allSlots = [];

        foreach ($sections as $section) {
            foreach ($section->schedules as $schedule) {
                $start = strtotime($schedule->start_time);
                $end   = strtotime($schedule->end_time);
                $day   = $schedule->day;

                foreach ($allSlots as $slot) {
                    if ($slot['day'] === $day) {
                        if ($start < $slot['end'] && $end > $slot['start']) {
                            $this->errors->add(
                                'conflict',
                                "Schedule conflict detected on {$day} between '{$slot['subject']}' and '{$section->subject->code}'."
                            );
                        }
                    }
                }

                $allSlots[] = [
                    'day'     => $day,
                    'start'   => $start,
                    'end'     => $end,
                    'subject' => $section->subject->code,
                ];
            }
        }
    }

    public function checkCapacity(array $sectionIds): void
    {
        DB::transaction(function () use ($sectionIds) {
            $sections = Section::whereIn('id', $sectionIds)->lockForUpdate()->get();

            foreach ($sections as $section) {
                if ($section->isFull()) {
                    $this->errors->add(
                        'capacity',
                        "Section '{$section->section_code}' for '{$section->subject->code}' is already full."
                    );
                }
            }
        });
    }
}
